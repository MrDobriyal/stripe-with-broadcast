<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\MessageSent;
use App\Models\Notification;

class CreateNotification
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
     public function handle(MessageSent $event)
    {
        Notification::create([
            'user_id' => $event->message->receiver_id,
            'title' => 'New Message',
            'body' => $event->message->message
        ]);
    }   
}
