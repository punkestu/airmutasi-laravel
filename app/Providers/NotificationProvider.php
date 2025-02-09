<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class NotificationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                $hasUnreadNotifications = false;
                if ($user->role->name === 'admin') {
                    $hasUnreadNotifications = Notification::where('to', null)->where('is_read', false)->count() > 0 || UserNotification::where('is_read', false)->count() > 0;
                }

                $view->with('adminHasUnreadNotifications', $hasUnreadNotifications);
            }
        });
    }
}
