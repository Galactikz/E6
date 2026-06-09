<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePremiumSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cette fonctionnalité nécessite un abonnement Premium.',
                    'upgrade_url' => route('pricing'),
                ], 402);
            }

            return redirect()->route('pricing')->with('info', 'Cette fonctionnalité est réservée aux membres Premium.');
        }

        return $next($request);
    }
}
