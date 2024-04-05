<?php

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    

    public function __construct(public Event $event)
    {
        
        $this->event = $event;
    }
}
