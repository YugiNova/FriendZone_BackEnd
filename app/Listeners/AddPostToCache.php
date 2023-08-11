<?php

namespace App\Listeners;

use App\Events\CreatePost;
use App\Models\Friendship;
use App\Repository\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class AddPostToCache
{
    /**
     * Create the event listener.
     */

    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(CreatePost $event): void
    {
        $friends = $this->userRepository->ownerFriendshipsByStatus('friend');
        if($event->post['status'] = 'friend' || $event->post['status'] = 'public'){
            
            foreach($friends as $friend){
                $redis = Redis::connection();
                $redis->lpush('newsfeed:'.$friend->id,$event->post['id']);
            }
        }
        $event->author_friends = $friends;
    }
}
