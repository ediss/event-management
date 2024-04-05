<?php

namespace App\Jobs;

use App\Mail\EventInvitationMail;
use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class JobNotifyUsersEventWasCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    public function __construct(protected Event $event)
    {
        $this->event = $event;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::all()->take(4);

        

        foreach($users as $user) {
            Mail::to($user)->send(
                new EventInvitationMail($this->event)
            );
        }
    }
}
