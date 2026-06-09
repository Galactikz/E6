<?php

namespace App\Policies;

use App\Models\ChecklistTask;
use App\Models\User;

class ChecklistTaskPolicy
{
    public function view(User $user, ChecklistTask $task): bool
    {
        return $user->id === $task->wedding->user_id;
    }

    public function update(User $user, ChecklistTask $task): bool
    {
        return $user->id === $task->wedding->user_id;
    }

    public function delete(User $user, ChecklistTask $task): bool
    {
        return $user->id === $task->wedding->user_id;
    }
}
