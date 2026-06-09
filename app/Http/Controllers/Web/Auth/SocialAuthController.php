<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct(private readonly SocialAuthService $socialAuthService) {}

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google', 'apple']), 400);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google', 'apple']), 400);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['social' => 'Authentification échouée.']);
        }

        $user = $this->socialAuthService->findOrCreateUser($socialUser, $provider);
        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
