<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('admin_authenticated') || $request->session()->get('admin_authenticated') !== true) {
            return redirect()->route('admin.login')->with('error', 'Silakan masuk sebagai admin terlebih dahulu.');
        }

        return $next($request);
    }
}
