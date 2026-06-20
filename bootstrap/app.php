<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'onboarding' => \App\Http\Middleware\EnsureOnboardingCompleted::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
        ]);
        
        $middleware->trimStrings(except: [
            fn (\Illuminate\Http\Request $request) => $request->is('admin/*') || $request->is('submateri/*/quiz/submit')
        ]);

        $middleware->convertEmptyStringsToNull(except: [
            fn (\Illuminate\Http\Request $request) => $request->is('admin/*') || $request->is('submateri/*/quiz/submit')
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
