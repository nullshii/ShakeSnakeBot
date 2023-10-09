<?php

namespace App\Providers;

use App\Events\EatEvent;
use App\Events\LoseEvent;
use App\Events\WinEvent;
use App\Listeners\EatEventListener;
use App\Listeners\LoseEventListener;
use App\Listeners\WinEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WinEvent::class => [
            WinEventListener::class,
        ],
        EatEvent::class => [
            EatEventListener::class,
        ],
        LoseEvent::class => [
            LoseEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
