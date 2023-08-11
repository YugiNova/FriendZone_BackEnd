<?php

namespace App\Listeners;

use App\Events\AcceptFriendRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseFriendCount
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
        $sendUserProfile = $event->friendRequest->sendUser->profile;
        $recieveUserProfile = $event->friendRequest->recieveUser->profile;
        $sendUserProfile->update([
            'friends_count' => $sendUserProfile['friends_count'] + 1
        ]);

        $recieveUserProfile->update([
            'friends_count' => $recieveUserProfile['friends_count'] + 1
        ]);
    }
}
