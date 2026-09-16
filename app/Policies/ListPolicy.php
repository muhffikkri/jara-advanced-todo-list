<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;

class ListPolicy
{
    /**
     * Hanya pemilik yang boleh melihat daftar.
     */
    public function view(User $user, TodoList $list): bool
    {
        return $list->owner_id === $user->id;
    }

    /**
     * Hanya pemilik yang boleh mengubah daftar.
     */
    public function update(User $user, TodoList $list): bool
    {
        return $list->owner_id === $user->id;
    }

    /**
     * Hanya pemilik yang boleh menghapus daftar.
     */
    public function delete(User $user, TodoList $list): bool
    {
        return $list->owner_id === $user->id;
    }
}
