<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;

class ListMemberPolicy
{
    /**
     * Determine whether the user can manage members of the list.
     */
    public function manageMembers(User $user, TodoList $list): bool
    {
        return $user->id === $list->owner_id;
    }
}
