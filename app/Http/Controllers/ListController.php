<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListRequest;
use App\Http\Requests\UpdateListRequest;
use App\Models\TodoList;
use App\Support\ListProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ListController extends Controller
{
    /**
     * Daftar milik & diikuti pengguna aktif.
     */
    public function index(Request $request): JsonResponse|View
    {
        $userId = auth()->id();

        $query = TodoList::query()->where('owner_id', $userId);

        if (Schema::hasTable('list_user')) {
            $followedIds = DB::table('list_user')->where('user_id', $userId)->pluck('list_id');

            if ($followedIds->isNotEmpty()) {
                $query->orWhereIn('id', $followedIds);
            }
        }

        $lists = $query->withCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($query) => $query->where('is_completed', true),
        ])->latest()->get();

        if ($request->expectsJson()) {
            return response()->json($lists);
        }

        return view('lists.index', ['lists' => $lists]);
    }

    /**
     * Buat daftar baru, pemilik otomatis diisi user aktif.
     */
    public function store(StoreListRequest $request): JsonResponse|RedirectResponse
    {
        $list = TodoList::query()->create([
            'owner_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        if ($request->expectsJson()) {
            return response()->json($list, 201);
        }

        return redirect()->route('lists.show', $list)->with('status', 'Daftar berhasil dibuat.');
    }

    /**
     * Tampilkan satu daftar milik pengguna.
     */
    public function show(Request $request, TodoList $list): JsonResponse|View
    {
        Gate::authorize('view', $list);

        $list->load(['tasks' => fn ($query) => $query->latest(), 'members']);

        $total = $list->tasks->count();
        $completed = $list->tasks->where('is_completed', true)->count();
        $progress = ListProgress::percentage($total, $completed);

        if ($request->expectsJson()) {
            return response()->json($list);
        }

        return view('lists.show', [
            'list' => $list,
            'tasks' => $list->tasks,
            'members' => $list->members,
            'progress' => $progress,
            'totalTasks' => $total,
            'completedTasks' => $completed,
            'isOwner' => $list->owner_id === $request->user()?->id,
        ]);
    }

    /**
     * Ubah nama/deskripsi daftar (khusus owner).
     */
    public function update(UpdateListRequest $request, TodoList $list): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $list);

        $list->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json($list->refresh());
        }

        return back()->with('status', 'Daftar berhasil diperbarui.');
    }

    /**
     * Hapus daftar beserta tugas & anggota secara atomik (khusus owner).
     */
    public function destroy(Request $request, TodoList $list): JsonResponse|RedirectResponse
    {
        Gate::authorize('delete', $list);

        DB::transaction(function () use ($list): void {
            if (Schema::hasTable('tasks')) {
                DB::table('tasks')->where('list_id', $list->id)->delete();
            }

            if (Schema::hasTable('list_user')) {
                DB::table('list_user')->where('list_id', $list->id)->delete();
            }

            $list->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('lists.index')->with('status', 'Daftar berhasil dihapus.');
    }
}
