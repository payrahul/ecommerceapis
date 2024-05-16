<?php

namespace App\Providers;

use App\Events\NewUserRegisteredEvent;
use App\Listeners\SendMailListener;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\PostCreated;
use App\Listeners\SendPostCreatedNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    //     NewUserRegisteredEvent::Class =>[
    //         SendMailListener::Class,
    //     ]
    // ];

    protected $listen = [
        PostCreated::class => [
            SendPostCreatedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }
}
