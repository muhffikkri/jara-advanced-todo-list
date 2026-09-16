<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;

class ListPolicy
{
    /**
     * Pemilik atau anggota boleh melihat daftar.
     */
    public function view(User $user, TodoList $list): bool
    {
        if ($list->owner_id === $user->id) {
            return true;
        }

        return $list->members()->where('users.id', $user->id)->exists();
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
