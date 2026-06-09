<?php

namespace App\Policies;

use App\Models\TablePlan;
use App\Models\User;

class TablePlanPolicy
{
    public function view(User $user, TablePlan $plan): bool
    {
        return $user->id === $plan->wedding->user_id;
    }

    public function update(User $user, TablePlan $plan): bool
    {
        return $user->id === $plan->wedding->user_id;
    }

    public function delete(User $user, TablePlan $plan): bool
    {
        return $user->id === $plan->wedding->user_id;
    }
}
