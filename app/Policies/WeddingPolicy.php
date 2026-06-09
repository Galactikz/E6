<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wedding;

class WeddingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Wedding $wedding): bool
    {
        if ($user->id === $wedding->user_id) return true;

        return $wedding->collaborators()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    public function create(User $user): bool
    {
        $plan = $user->currentPlan();
        if (!$plan) return $user->weddings()->count() === 0;

        return $user->weddings()->count() < $plan->max_weddings;
    }

    public function update(User $user, Wedding $wedding): bool
    {
        if ($user->id === $wedding->user_id) return true;

        return $wedding->collaborators()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereIn('role', ['editor', 'admin'])
            ->exists();
    }

    public function delete(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id;
    }

    public function share(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id && $user->isPremium();
    }
}
