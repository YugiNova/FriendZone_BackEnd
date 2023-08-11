<?php

namespace App\Repository;

use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

use function PHPSTORM_META\map;

class PostRepository
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPostsByUser($userId, $cursor = null)
    {
        try {
            $redis = Redis::connection();
            $post = [];
            if ($cursor == "loadmore") {
                $posts = $redis->lrange('newsfeed:' . $userId, 1, 6); //list of post id
            } else {
                $posts = $redis->lrange('newsfeed:' . $userId, 0, 5); //list of post id
            }
            $newsfeed = [];

            if (count($posts) > 0) {

                foreach ($posts as $post) {

                    $postItem = Post::where('id', $post)->first();
                    $postItem->images;
                    $postItem->author;
                    $newsfeed[] = $postItem;

                    // $redis->lrem('newsfeed:'.$userId,0,$post);
                }
            } else {
                $friends = $this->userRepository->ownerFriendshipsByStatus('friend')
                    ->map(function ($friend) {
                        return $friend->id;
                    });
                $newsfeed = Post::where('status', 'public')->whereNotIn('user_id', $friends)
                    ->get()->map(function ($post) {
                        $post->images;
                        $post->author;
                        return $post;
                    });
            }

            return  $newsfeed;
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPostsByUserProfile($userId)
    {
        try {
      
            if($userId == Auth::user()->id){
                $posts = Post::where('user_id',$userId);
            }
            else{
                $friendship = Friendship::where(function ($query) use ($userId){
                    $query->where('user_id',$userId)->where('friend_id',Auth::user()->id)->where('status','friend');
                })->orWhere(function ($query) use ($userId){
                    $query->where('user_id',Auth::user()->id)->where('friend_id',$userId)->where('status','friend');
                });

                if($friendship){
                    $posts = Post::where('user_id',$userId)->where('status','public')->orWhere('status','friend');
                }
                else{
                    $posts = Post::where('user_id',$userId)->where('status','public');
                }
            }
     

            $posts = $posts->orderBy('created_at','desc')->paginate(5);
      
            if ($posts->hasMorePages()) {
                $nextPage = $posts->currentPage() + 1;
            }
   
            $posts = $posts->map(function ($post){
                $post->images;
                $post->author;
                return $post;
            });
            
            return  [
                'nextPage' => $nextPage ?? null,
                'posts' => $posts
            ];
   
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPostById($post_id)
    {
        try {
            $post = Post::where('id', $post_id)->first();


            return $post;
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCommentsByPost($post_id)
    {
        try {
            $comments = Comment::where('post_id', $post_id)->where('parent_slug', null)->orderBy('created_at', 'desc')->paginate(5);

            if ($comments->hasMorePages()) {
                $nextPage = $comments->currentPage() + 1;
            }

            $data = $comments->map(function ($comment) {
                $comment->author;
                return $comment;
            });

            return [
                'nextPage' => $nextPage ?? null,
                'comments' => $data
            ];
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCommentsByComment($comment_slug, $post_id)
    {
        try {
            $comments = Comment::where('post_id', $post_id)->where('parent_slug', $comment_slug)->orderBy('created_at', 'desc')->paginate(5);
         
            if ($comments->hasMorePages()) {
                $nextPage = $comments->currentPage() + 1;
            }
            $data = $comments->map(function ($comment) {
                $comment->author;
                return $comment;
            });

            return [
                'nextPage' => $nextPage ?? null,
                'comments' => $data
            ];

        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
