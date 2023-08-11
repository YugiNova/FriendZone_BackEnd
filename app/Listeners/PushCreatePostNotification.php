<?php

namespace App\Listeners;

use App\Events\CreatePost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class PushCreatePostNotification
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
    public function handle(CreatePost $event): void
    {
        foreach($event->notificaitons as $notification){
            Redis::publish($notification->recieveUser->id.':notifications',json_encode($notification));
        }
    }
}
