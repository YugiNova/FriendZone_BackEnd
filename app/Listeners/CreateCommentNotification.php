<?php

namespace App\Listeners;

use App\Events\CreateComment;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CreateCommentNotification
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
        $author = $comment->author;
        if ($comment->parent_slug != "") //reply a comment
        {
            $parentComment = Comment::where('slug', $comment->parent_slug)->first();
            $authorParentComment = $parentComment->author;
            if ($authorParentComment->id != Auth::user()->id) {
                $notification = Notification::create([
                    'send_id' => Auth::user()->id,
                    'recieve_id' => $authorParentComment->id,
                    'type' => 'comment',
                    'content' => 'reply your comment',
                    'source_id' => $comment->id
                ]);

                $notification->sendUser;
                $notification->recieveUser;
                $event->notification = $notification;
            }
        } else {
            $parentPost = Post::where('id', $comment->post_id)->first();
            if ($parentPost->author->id != Auth::user()->id) {
                $notification = Notification::create([
                    'send_id' => Auth::user()->id,
                    'recieve_id' => $parentPost->author->id,
                    'type' => 'comment',
                    'content' => 'comment to your post',
                    'source_id' => $comment->id
                ]);

                $notification->sendUser;
                $notification->recieveUser;
                $event->notification = $notification;
            }
        }
    }
}
