<?php

namespace App\Listeners;

use App\Events\GetUsers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\User;


class ListenGetUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\GetUsers  $event
     * @return void
     */
    public function handle(GetUsers $event)
    {
        //

        // User::all()
        echo 'Received message: ' . $event->message . User::all();
    }
}
