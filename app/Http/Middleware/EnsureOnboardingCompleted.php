<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->onboarding_completed) {
            if (!$user->interest) {
                return redirect()->route('onboarding.interest');
            }
            if (!$user->focus) {
                return redirect()->route('onboarding.focus');
            }
        }

        return $next($request);
    }
}
