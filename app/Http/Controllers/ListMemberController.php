<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreListMemberRequest;
use App\Models\TodoList;
use App\Models\User;
use App\Policies\ListMemberPolicy;
use Illuminate\Support\Facades\Gate;

class ListMemberController extends Controller
{
    /**
     * Display a listing of the lists the user has joined.
     */
    public function index(Request $request)
    {
        $lists = $request->user()->joinedLists()->get();
        
        // Return JSON for now, or a view if it existed
        return response()->json($lists);
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(StoreListMemberRequest $request, TodoList $todoList)
    {
        Gate::authorize('manageMembers', $todoList);

        $todoList->members()->syncWithoutDetaching([
            $request->validated('user_id') => ['added_by' => $request->user()->id]
        ]);

        return response()->json(['message' => 'Member added successfully']);
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Request $request, TodoList $todoList, User $member)
    {
        Gate::authorize('manageMembers', $todoList);

        $todoList->members()->detach($member->id);

        return response()->json(['message' => 'Member removed successfully']);
    }
}
