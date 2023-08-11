<?php

namespace App\Listeners;

use App\Events\AcceptFriendRequest;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAcceptNotification
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
    public function handle(AcceptFriendRequest $event): void
    {   
        $recieveUser = $event->friendRequest->recieveUser->display_name;
        $notification = Notification::create([
            'send_id' => $event->friendRequest->user_id,
            'recieve_id' => $event->friendRequest->friend_id,
            'type' => 'request',
            'content' => $recieveUser.' accepted your friend request',
            'is_read' => false,
            'source_id' => $event->friendRequest->id
        ]);

        $event->notification = $notification;
    }
}
