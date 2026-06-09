<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;

class GuestPolicy
{
    public function view(User $user, Guest $guest): bool
    {
        return $user->id === $guest->wedding->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Guest $guest): bool
    {
        return $user->id === $guest->wedding->user_id;
    }

    public function delete(User $user, Guest $guest): bool
    {
        return $user->id === $guest->wedding->user_id;
    }
}
