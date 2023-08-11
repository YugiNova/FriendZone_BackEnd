<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Events\CreatePost;
use App\Exceptions\PostException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\User;
use App\MyHelper;
use App\Repository\PostRepository;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $hepler;
    private $cloudinary;
    private $postRepository;

    public function __construct(MyHelper $hepler,Cloudinary $cloudinary, PostRepository $postRepository)
    {
        $this->hepler = $hepler;
        $this->cloudinary = $cloudinary;
        $this->postRepository = $postRepository;
    }

    public function getPostsByUserProfile(Request $request,User $user){
        try {
      
            $posts = $this->postRepository->getPostsByUserProfile($user->id);

            return $this->hepler->custom_response('Get newsfeed successfull',$posts);
        } catch (\Exception $e) {
           
        }
    }

    public function getPosts(Request $request){
        try {
            $cursor = $request->query('cursor') ?? null;
            $newsfeed = $this->postRepository->getPostsByUser(Auth::user()->id,$cursor);

            return $this->hepler->custom_response('Get newsfeed successfull',$newsfeed);
        } catch (\Exception $e) {
           
        }
    }

    public function createPost(CreatePostRequest $request){
        try {
            DB::beginTransaction();
            if(!$request->content && !$request->children_post_id && !$request->hasFile('image')){
                throw new \Exception('A post need to have some content or picture');
            }

            $post = Post::create([
                'user_id' => Auth::user()->id,
                'content' => $request->content ?? null,
                'children_post_id' => $request->children_post_id ?? null,
                'status' => $request->status
            ]);

            if($request->hasFile('image')){
                foreach ($request->file('image') as $photo) {
                    $url = $photo->storeOnCloudinary('FriendZone/'.Auth::user()->id)->getSecurePath();
                    $postPhoto = Gallery::create([
                        'user_id' => Auth::user()->id,
                        'post_id' => $post->id,
                        'image_url' => $url
                    ]);
                };
            }

            $post->images;
            event(new CreatePost($post));
        
            DB::commit();
            return $this->hepler->custom_response("Create post successfull",$post);  
        } catch (\Exception $e) {
            DB::rollBack();
            throw new PostException($e);
        }
    }

    public function deletePost(){
        try {
            
        } catch (\Exception $e) {
            throw new PostException($e);
        }
    }
}

?>