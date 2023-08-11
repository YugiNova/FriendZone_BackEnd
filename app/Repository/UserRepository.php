<?php
namespace App\Repository;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Pipeline;

class UserRepository{

    private function getFriendshipByStatus($userId,$status=null) {
        $friendships = Friendship::where('user_id',$userId)->orWhere('friend_id',$userId)->get()
        ->map(function ($friendship) use ($userId){
            if($friendship->user_id == $userId)
            {
                return [
                    'friend_id' => $friendship->friend_id,
                    'status' => $friendship->status 
                    //status : friend-> already friend | request -> sent request, wait for o accept
                ];
            }
            else if ($friendship->friend_id == $userId){  
                return [
                    'friend_id' => $friendship->user_id,
                    'status' => $friendship->status == "request" ? "accept" : $friendship->status
                    //status : friend-> already friend | accept -> recieved request, wait for you accept
                ];
            }
        });

        if($status){
            $friendships = $friendships->filter(function ($friendship) use ($status) {
                if($friendship['status'] == $status)
                    return $friendship;
            });
            $friendships = $friendships->values();
        }
        return $friendships;
    }
    public function all() {
        $pipelines = [
            \App\Filters\ByKeyword::class,
            // \App\Filters\Paginate::class,
            // \App\Filters\ByFriendship::class,
        ];
        $users = Pipeline::send(User::query()->where('role','user'))
        ->through($pipelines)
        ->thenReturn();
        $ownerID = Auth::user()->id;
        $friendships = $this->getFriendshipByStatus($ownerID);

        $users = $users->paginate(8)->map(function ($user) use ($friendships,$ownerID) {
            foreach($friendships as $friendship){
                if($user->id == $friendship['friend_id']){
                    $user['friendship'] = $friendship['status'];
                }
                else if ($user->id == $ownerID){
                    $user['friendship'] = "self";
                }
                else{
                    $user['friendship'] == null;
                }
            }
            // $user['profile'] = $user->profile;
            return $user;
        });
        return $users;
    }


    public function friendsListByUserID($userId) {
        $ownerId = Auth::user()->id;
        $userFriendships = $this->getFriendshipByStatus($userId,'friend');
        $ownerFriendships = $this->getFriendshipByStatus($ownerId);

        //Transform to owner friendship status
        $userFriendshipsWithOwnerStatus = $userFriendships->map(function($userFriendship) use ($ownerFriendships){
            foreach($ownerFriendships as $ownerFriendship){
                if($ownerFriendship['friend_id'] == $userFriendship['friend_id']){
                    $userFriendship['status'] = $ownerFriendship['status'];
                }
                else{
                    $userFriendship['status'] = null;
                }
            }
            return $userFriendship;
        });

        //Transform to User Eloquent
        $userFriendList = $userFriendshipsWithOwnerStatus->map(function ($userFriend) {
            $friend = User::find($userFriend['friend_id'])->first();
            $friend['friendship'] = $userFriend['status'];
            return $friend;
        });
        return $userFriendList;
    }

    public function ownerFriendshipsByStatus($status) {
        $ownerId = Auth::user()->id;
        $ownerFriendships = $this->getFriendshipByStatus($ownerId,$status);
        $ownerFriendships = $ownerFriendships->map(function ($ownerFriendship) {
            $friend = User::where('id',$ownerFriendship['friend_id'])->first();
            $friend['friendship'] = $ownerFriendship['status'];
            return $friend;
        });
        return $ownerFriendships;
    }
} 
?>