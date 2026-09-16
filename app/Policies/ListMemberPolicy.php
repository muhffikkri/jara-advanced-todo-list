<?php

namespace App\Policies;

use App\Models\User;

class ListMemberPolicy
{
    /**
     * Determine whether the user can manage members of the list.
     */
    public function manageMembers(User $user, \App\Models\TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id;
    }
}
