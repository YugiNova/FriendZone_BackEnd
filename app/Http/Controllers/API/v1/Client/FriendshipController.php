<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Events\AcceptFriendRequest;
use App\Events\SendFriendRequest;
use App\Exceptions\FriendshipException;
use App\Http\Controllers\Controller;
use App\Models\Friendship;
use App\Models\Notification;
use App\Models\User;
use App\MyHelper;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class FriendshipController extends Controller
{
    
    private $hepler;

    private $userRepository;

    public function __construct(MyHelper $hepler,UserRepository $userRepository)
    {
        $this->hepler = $hepler;
        $this->userRepository = $userRepository;
    }

    public function sendFriendRequest(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'recieveId' => ['required'],
            ]);
            $sendUser = Auth::user()->id;
            $recieveUser = $data['recieveId'];

            //check if request exists
            $check = Friendship::where('user_id',$sendUser)->where('friend_id',$recieveUser)->first();
            if($check){
                throw new Exception('Already send friend request to this user');
            }

            $sendRequest = Friendship::create([
                'user_id' => $sendUser,
                'friend_id' => $recieveUser,
                'status' => 'request'
            ]);

            event(new SendFriendRequest($sendRequest));
            DB::commit();
            return $this->hepler->custom_response("Send friend request successfull",$sendRequest);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new FriendshipException($e);
        }
    }

    public function acceptFriendRequest(Request $request){
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'sentId' => ['required'],
            ]);
            // $sentUser = User::find($data['sentId']);

            $acceptUser = Auth::user()->id;

            //check if request exists
            $friendRequest = Friendship::where('user_id',$data['sentId'])->where('friend_id',$acceptUser)->first();

            if(!$friendRequest){
                throw new Exception('Oops! Request no longer exists. May be this person withdraw the request');
            }

            $friendRequest->update(['status'=>'friend']);
            event(new AcceptFriendRequest($friendRequest));
            DB::commit();
            return $this->hepler->custom_response("Accept friend request successfull");
        } catch (\Exception $e) {
            DB::rollBack();
            throw new FriendshipException($e);
        }
    }

    public function removeFriendRequest(Request $request){
        try {
            $data = $request->validate([
                'recieveId' => ['required'],
            ]);
            // $requestUser = User::find($data['recieveId']);
            $sentId = Auth::user()->id;
            $friendRequest = Friendship::where('user_id',$sentId)->where('friend_id',$data['recieveId'])->first();
            Notification::where('source_id',$friendRequest->id)->delete();
            $friendRequest->delete();
            return $this->hepler->custom_response("Delete friend request successfull");
        } catch (\Exception $e) {
            throw new FriendshipException($e);
        }
    }

    public function getFriendRequests(Request $request){
        try {
            $friendRequests = $this->userRepository->ownerFriendshipsByStatus('accept');
            return $this->hepler->custom_response('Get friend request list successfull',$friendRequests);
        } catch (\Exception $e) {
            throw new FriendshipException($e);
        }
    }
}
