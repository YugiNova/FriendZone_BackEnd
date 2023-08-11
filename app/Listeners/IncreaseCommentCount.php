<?php

namespace App\Listeners;

use App\Events\CreateComment;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseCommentCount
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
        $post_id = $comment->post_id;
        $post = Post::where('id',$post_id)->first();
        $post->update(['comments_count' => $post->comments_count + 1]);
        if($comment->parent_slug != ""){
            $parentComment = Comment::where('slug',$comment->parent_slug)->first()
                            ->update(['replies_count'=> $comment->replies_count +1]);
        }
    }
}
