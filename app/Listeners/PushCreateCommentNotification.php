<?php

namespace App\Listeners;

use App\Events\CreateComment;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class PushCreateCommentNotification
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
    public function handle(CreateComment $event): void
    {
        $comment = $event->comment;
        if($event->notification != []){
            if($comment->parent_slug != "") //reply a comment
            {
                $parentComment = Comment::where('slug',$comment->parent_slug)->first();
                $authorParentComment = $parentComment->author;
                Redis::publish($authorParentComment->id.':notifications',json_encode($event->notification));
            }
            else{
                $parentPost = Post::where('id',$comment->post_id)->first();
                $author = $parentPost->author->id;
                Redis::publish($author.':notifications',json_encode($event->notification));
            }
        }
    }
}
