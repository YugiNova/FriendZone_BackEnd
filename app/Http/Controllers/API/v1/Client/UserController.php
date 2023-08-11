<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\PlaceRequest;
use App\Http\Requests\WorkEducationRequest;
use App\Models\Friendship;
use App\Models\User;
use App\Models\UserContact;
use App\Models\UserPlace;
use App\Models\UserProfile;
use App\Models\UserWorkEducation;
use Exception;
use Illuminate\Http\Request;
use App\MyHelper;
use App\Repository\UserRepository;
use Cloudinary\Cloudinary;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Pipeline;

class UserController extends Controller
{
    private $cloudinary;
    private $hepler;
    private $userRepository;
    public function __construct(Cloudinary $cloudinary, MyHelper $hepler,UserRepository $userRepository)
    {
        $this->cloudinary = $cloudinary;
        $this->hepler = $hepler;
        $this->userRepository = $userRepository;
    }

    public function getUserList(Request $request) {
        try {
            $userId = $request->query('userId');
            $status = $request->query('status');
            if($userId){
                $users = $this->userRepository->friendsListByUserID($userId);
            }
            else if($status){
                $users = $this->userRepository->ownerFriendshipsByStatus($status);
            }
            else{
                $users = $this->userRepository->all();
            }
            return $this->hepler->custom_response("Get user list successfull", $users);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function getProfile($slug)
    {
        try {
            $user = User::where('slug', $slug)->first();
            if (!$user) {
                throw new Exception('Profile not found');
            }
            $user->profile;
            $user->contacts;
            $user->works_educations;
            $user->places;

            return $this->hepler->custom_response('Get user profile successfull', $user);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateTheme(Request $request, $slug)
    {
        try {
            $user = User::where('slug', $slug)->first();
            if (!$user) {
                throw new Exception('Profile not found');
            }
            $theme = $request->theme;
            $userProfile = UserProfile::where('user_id', $user->id)->update(['theme' => $theme]);

            return $this->hepler->custom_response("Update theme to $theme successfull", $user->profile);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateColor(Request $request, $slug)
    {
        try {
            $user = User::where('slug', $slug)->first();
            if (!$user) {
                throw new Exception('Profile not found');
            }
            $color = $request->color;
            $userProfile = UserProfile::where('user_id', $user->id)->update(['color' => $color]);

            return $this->hepler->custom_response("Update color to $color successfull", $user->profile);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateDob(Request $request, UserProfile $profile){
        try {
            $validated = $request->validate([
                'dob' => 'required',
            ]);
            if (!$profile) {
                throw new Exception('Profile not found');
            }
            $profile->update([
                'dob' => $request->dob
            ]);

            return $this->hepler->custom_response("Update dob successfull", $profile->dob);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateIntroduce(Request $request, UserProfile $profile){
        try {
            $validated = $request->validate([
                'introduce' => 'required',
            ]);
            if (!$profile) {
                throw new Exception('Profile not found');
            }
            $profile->update([
                'introduce' => $request->introduce
            ]);

            return $this->hepler->custom_response("Update introduce successfull", $profile->introduce);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function createContact(ContactRequest $request)
    {
        try {
            $contact = UserContact::create([
                'user_id' => $request->user_id,
                'content' => $request->content,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Create user contact successfull", $contact);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateContact(ContactRequest $request, UserContact $contact)
    {
        try {
            $contact->update([
                'content' => $request->content,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Update user contact successfull", $contact);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function deleteContact(UserContact $contact){
        try {
            $contact->delete();

            return $this->hepler->custom_response("Delete user contact successfull",);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function createPlace(PlaceRequest $request)
    {
        try {
            $place = UserPlace::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Create user place successfull", $place);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updatePlace(PlaceRequest $request, UserPlace $place)
    {
        try {
            $place->update([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Update user place successfull", $place);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function deletePlace(UserPlace $place){
        try {
            $place->delete();

            return $this->hepler->custom_response("Delete user place successfull", $place);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function createWorkEducation(WorkEducationRequest $request)
    {
        try {
            $workeducation = UserWorkEducation::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'type' => $request->type,
                'year_start' => $request->year_start,
                'year_end' => $request->year_end,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Create user work education successfull", $workeducation);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateWorkEducation(WorkEducationRequest $request, UserWorkEducation $workeducation)
    {
        try {
            $workeducation->update([
                'name' => $request->name,
                'year_start' => $request->year_start,
                'year_end' => $request->year_end,
                'type' => $request->type,
                'status' => $request->status
            ]);

            return $this->hepler->custom_response("Update user work education successfull", $workeducation);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function deleteWorkEducation(UserWorkEducation $workeducation){
        try {
            $workeducation->delete();

            return $this->hepler->custom_response("Delete user work education successfull", $workeducation);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateCoverImage(Request $request, UserProfile $profile){
        try {
            $validated = $request->validate([
                'image' => [
                    'required','image',
                    'mimes:jpeg,png',
                    'mimetypes:image/jpeg,image/png',
                    'max:2048',
                ]
            ]);

            if(!$profile){
                throw new Exception('Profile not found');
            }

            //Delete old image
            if(!is_null($profile->cover_image_url)){
                $this->cloudinary->uploadApi()->destroy($this->hepler->getPublicIdFromUrl($profile->cover_image_url),['type'=>'upload']);
            }
           
            //Create new image
            $result = $request->file('image')->storeOnCloudinary('FriendZone/'.$profile->user_id);
            $coverUrl = $result->getSecurePath();

            $profile->update([
                'cover_image_url'=> $coverUrl
            ]);

            return $this->hepler->custom_response("Upload cover image successfull",$coverUrl);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateAvatar(Request $request, User $user){
        try {
            $validated = $request->validate([
                'image' => [
                    'required','image',
                    'mimes:jpeg,png',
                    'mimetypes:image/jpeg,image/png',
                    'max:2048', 
                ]
            ]);

            //Delete old image
            if(!is_null($user->avatar_url)){
                $this->cloudinary->uploadApi()->destroy($this->hepler->getPublicIdFromUrl($user->avatar_url));
            }
          
            //Create new image
            $result = $request->file('image')->storeOnCloudinary('FriendZone/'.$user->id);
            $avatarUrl = $result->getSecurePath();

            $user->update([
                'avatar_url'=> $avatarUrl
            ]);

            return $this->hepler->custom_response("Upload avatar successfull",$avatarUrl);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }
}
