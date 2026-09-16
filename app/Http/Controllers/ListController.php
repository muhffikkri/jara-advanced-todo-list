<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListRequest;
use App\Http\Requests\UpdateListRequest;
use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class ListController extends Controller
{
    /**
     * Daftar milik & diikuti pengguna aktif.
     */
    public function index(): JsonResponse
    {
        $userId = auth()->id();

        $query = TodoList::query()->where('owner_id', $userId);

        if (Schema::hasTable('list_user')) {
            $followedIds = DB::table('list_user')->where('user_id', $userId)->pluck('list_id');

            if ($followedIds->isNotEmpty()) {
                $query->orWhereIn('id', $followedIds);
            }
        }

        return response()->json($query->latest()->get());
    }

    /**
     * Buat daftar baru, pemilik otomatis diisi user aktif.
     */
    public function store(StoreListRequest $request): JsonResponse
    {
        $list = TodoList::query()->create([
            'owner_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        return response()->json($list, 201);
    }

    /**
     * Tampilkan satu daftar milik pengguna.
     */
    public function show(TodoList $list): JsonResponse
    {
        Gate::authorize('view', $list);

        return response()->json($list);
    }

    /**
     * Ubah nama/deskripsi daftar (khusus owner).
     */
    public function update(UpdateListRequest $request, TodoList $list): JsonResponse
    {
        Gate::authorize('update', $list);

        $list->update($request->validated());

        return response()->json($list->refresh());
    }
}
