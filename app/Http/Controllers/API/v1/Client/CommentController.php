<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Events\CreateComment;
use App\Exceptions\PostException;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\MyHelper;
use App\Repository\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CommentController extends Controller
{
    private $hepler;
    private $cloudinary;
    private $postRepository;

    public function __construct(MyHelper $hepler, PostRepository $postRepository)
    {
        $this->hepler = $hepler;
        $this->postRepository = $postRepository;
    }

    public function createComment(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'post_id' => ['required'],
                'content' => ['required']
            ]);

            $user_id = Auth::user()->id;
            $post_id = $request->post_id;
            $parent_slug = $request->parent_slug ?? null;
            $content = $request->content;

            $comment = Comment::create([
                'user_id' => $user_id,
                'post_id' => $post_id,
                'parent_slug' => $parent_slug,
                'slug' => "",
                'content' => $content,
                'reactions_count' => 0,
                'replies_count' => 0
            ]);
            $slug = $parent_slug == null ? $comment->id :  $parent_slug.'/'.$comment->id;
            $comment->update(['slug' => $slug]);

            event(new CreateComment($comment));
            DB::commit();
            return $this->hepler->custom_response('Create comment successfull');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new PostException($e);
        }
    }

    public function getCommentsByPost(Request $request,Post $post){
        try {
            $comments = $this->postRepository->getCommentsByPost($post->id);
            return $this->hepler->custom_response('Get comments of post successfull',$comments);
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }

    public function getCommentsByComment(Request $request,Post $post,Comment $comment){
        try {
           
            $comments = $this->postRepository->getCommentsByComment($comment->slug,$post->id);
            return $this->hepler->custom_response('Get replies of comment successfull',$comments);
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }

    public function deleteComment(Request $request){
        try {
            
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }
}
