<?php

namespace App\Listeners;

use App\Events\SendFriendRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class PushRequestNotification
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
        $recieveId = $event->friendRequest->friend_id;
        Redis::publish($recieveId.':notifications', json_encode($event->notification));
    }
}
