<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Listeners\LogLastLoginTime;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Define the events and their associated listeners
     *
     * @var array[]
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LogLastLoginTime::class,
        ],
    ];

    /**
     * The boot method of this provider
     *
     * @return void
     */
    public function boot(): void {
        User::observe(UserObserver::class);
    }

    /**
     * Determine whether events should be automatically discovered
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
