<?php

namespace App\Listeners;

use App\Events\EventStored;
use App\Jobs\JobNotifyUsersEventWasCreated;

class NotifyUsersEventWasCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventStored $event): void
    {
        JobNotifyUsersEventWasCreated::dispatch($event->event);

    }
}
