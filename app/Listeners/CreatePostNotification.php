<?php

namespace App\Listeners;

use App\Events\CreatePost;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePostNotification
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
        if($event->post['status'] == 'friend' || $event->post['status'] == 'public'){
            foreach($event->author_friends as $friend){
                $notification = Notification::create([
                    'send_id' => $event->post['user_id'],
                    'recieve_id' => $friend->id,
                    'type' => 'post',
                    'content' => 'create a new post',
                    'is_read' => false,
                    'source_id' => $event->post['id']
                ]);
    
                $notification->sendUser;
                $notification->recieveUser;
                $event->notificaitons[] = $notification;
            }
        }
    }
}
