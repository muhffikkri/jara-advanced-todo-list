<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListMemberRequest;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ListMemberController extends Controller
{
    /**
     * Display a listing of the lists the user has joined.
     */
    public function index(Request $request): JsonResponse|View
    {
        $lists = $request->user()->joinedLists()->withCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($query) => $query->where('is_completed', true),
        ])->latest()->get();

        if ($request->expectsJson()) {
            return response()->json($lists);
        }

        return view('lists.joined', ['lists' => $lists]);
    }

    /**
     * Display the members of the given list.
     */
    public function show(Request $request, TodoList $list): JsonResponse|View
    {
        Gate::authorize('view', $list);

        $members = $list->members()->latest('list_user.created_at')->get();

        if ($request->expectsJson()) {
            return response()->json($members);
        }

        return view('lists.members', ['list' => $list, 'members' => $members]);
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(StoreListMemberRequest $request, TodoList $list): JsonResponse|RedirectResponse
    {
        Gate::authorize('manageMembers', $list);

        $list->members()->syncWithoutDetaching([
            $request->validated('user_id') => ['added_by' => $request->user()->id],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Member added successfully']);
        }

        return back()->with('status', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Request $request, TodoList $list, User $member): JsonResponse|RedirectResponse
    {
        Gate::authorize('manageMembers', $list);

        $list->members()->detach($member->id);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Member removed successfully']);
        }

        return back()->with('status', 'Anggota berhasil dihapus.');
    }
}
