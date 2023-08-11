<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Exceptions\PostException;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Reaction;
use App\MyHelper;
use App\Repository\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ReactionController extends Controller
{
    private $hepler;
    private $cloudinary;
    private $postRepository;

    public function __construct(MyHelper $hepler, PostRepository $postRepository)
    {
        $this->hepler = $hepler;
        $this->postRepository = $postRepository;
    }

    public function createReaction(Request $request)
    {
        try {
            $data = $request->validate([
                'type' => ['required']
            ]);

            if (!$request->post_id && !$request->comment_id) {
                throw new \Exception('You must like a post or a comment');
            }

            $reaction = Reaction::create([
                'user_id' => Auth::user()->id,
                'post_id' => $request->post_id ?? null,
                'comment_id' => $request->comment_id ?? null,
                'type' => $request->type
            ]);

            if (!$request->comment_id) //reaction to post
            {
                $post = Post::where('id', $request->post_id)->first();

                $post->update([
                    'reactions_count' => $post->reactions_count + 1
                ]);

                $author = $post->author;

                $notification = Notification::create([
                    'send_id' => Auth::user()->id,
                    'recieve_id' => $author->id,
                    'type' => 'like',
                    'content' => 'like your post',
                    'source_id' => $request->post_id
                ]);
                Redis::publish($author->id . ':notifications', json_encode($notification));
            }
            else //reaction to comment
            {
                $comment = Comment::where('id', $request->comment_id)->first();

                $comment->update([
                    'reactions_count' => $comment->reactions_count + 1
                ]);

                $author = $comment->author;

                $notification = Notification::create([
                    'send_id' => Auth::user()->id,
                    'recieve_id' => $author->id,
                    'type' => 'like',
                    'content' => 'like your post',
                    'source_id' => $request->post_id
                ]);
                Redis::publish($author->id . ':notifications', json_encode($notification));
            }
            
            return $this->hepler->custom_response('Get newsfeed successfull');
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }

    public function deleteReaction(Request $request)
    {
        try {
            $data = $request->validate([
                'post_id' => ['required'],
            ]);

            $author = Post::where('id', $request->post_id)->first()->author;

            $notification = Notification::create([
                'send_id' => Auth::user()->id,
                'recieve_id' => $author->id,
                'type' => 'like',
                'content' => 'like your post',
                'source_id' => $request->post_id
            ]);

            Redis::publish($author->id . ':notifications', json_encode($notification));

            return $this->hepler->custom_response('Get newsfeed successfull');
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }
}
