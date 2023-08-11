<?php

namespace App\Listeners;

use App\Events\AcceptFriendRequest;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteRequestNotification
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
       $requestNotification = Notification::where('source_id',$event->friendRequest->id)->delete();
    }
}
