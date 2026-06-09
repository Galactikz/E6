<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialAuthService
{
    public function findOrCreateUser(SocialUser $socialUser, string $provider): User
    {
        $field = "{$provider}_id";

        $user = User::where($field, $socialUser->getId())->first();

        if ($user) {
            $user->update([
                'avatar' => $socialUser->getAvatar(),
                'last_login_at' => now(),
            ]);
            return $user;
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                $field => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                'last_login_at' => now(),
            ]);
            return $user;
        }

        return User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            $field => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
            'last_login_at' => now(),
        ]);
    }
}
