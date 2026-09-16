<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TaskPolicy
{
    public function create(User $user, int $listId): bool
    {
        return $this->canAccessList($user, $listId);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->canAccessList($user, (int) $task->list_id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->canAccessList($user, (int) $task->list_id);
    }

    private function canAccessList(User $user, int $listId): bool
    {
        return DB::table('lists')
            ->where('lists.id', $listId)
            ->where(function (Builder $query) use ($user): void {
                $query
                    ->where('lists.owner_id', $user->id)
                    ->orWhereExists(function (Builder $membership) use ($user): void {
                        $membership
                            ->select('list_user.list_id')
                            ->from('list_user')
                            ->whereColumn('list_user.list_id', 'lists.id')
                            ->where('list_user.user_id', $user->id);
                    });
            })
            ->exists();
    }
}
