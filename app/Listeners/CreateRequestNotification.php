<?php

namespace App\Listeners;

use App\Events\SendFriendRequest;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateRequestNotification
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
    public function handle(SendFriendRequest $event): void
    {
        $sendUser = $event->friendRequest->sendUser->display_name;
       
        $notification = Notification::create([
            'send_id' => $event->friendRequest->user_id,
            'recieve_id' => $event->friendRequest->friend_id,
            'type' => 'request',
            'content' => 'sent you a friend request',
            'is_read' => false,
            'source_id' => $event->friendRequest->id
        ]);

        $notification->sendUser;
        $notification->recieveUser;
        $event->notification = $notification;
    }
}
