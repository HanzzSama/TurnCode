<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(
            ['partials.menu-panel', 'dashboard', 'history', 'jadwal', 'profile.edit', 'learning.course', 'learning.lesson'],
            function ($view) {
                if (auth()->check()) {
                    $notifications = auth()->user()->notifications()->latest()->take(10)->get();
                } else {
                    $notifications = collect();
                }
                $view->with('notifications', $notifications);
            }
        );
    }
}
