<?php

namespace App\Listeners;

use App\Events\AcceptFriendRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class PushAcceptNotification
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
        $recieveId = $event->friendRequest->user_id;
        Redis::publish($recieveId.':notification',json_encode($event->notification));
    }
}
