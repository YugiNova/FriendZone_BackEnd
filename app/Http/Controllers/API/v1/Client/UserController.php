<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helper\custom_response;

class UserController extends Controller
{
    public function getProfile($slug)
    {
        try {
            $user = User::where('slug',$slug)->first();
            if(!$user){
                throw new Exception('Profile not found');
            }
            $user->profile;
            $user->contacts;
            $user->works_educations;
            $user->places;

            return custom_response('Get user profile successfull',$user);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateTheme(Request $request,$slug)
    {
        try {
            $user = User::where('slug',$slug)->first();
            if(!$user){
                throw new Exception('Profile not found');
            }
            $theme = $request->theme;
            $userProfile = UserProfile::where('user_id',$user->id)->update(['theme'=>$theme]);

            return custom_response("Update theme to $theme successfull",$user->profile);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }

    public function updateColor(Request $request,$slug)
    {
        try {
            $user = User::where('slug',$slug)->first();
            if(!$user){
                throw new Exception('Profile not found');
            }
            $color = $request->color;
            $userProfile = UserProfile::where('user_id',$user->id)->update(['color'=>$color]);

            return custom_response("Update color to $color successfull",$user->profile);
        } catch (\Exception $e) {
            throw new UserException($e);
        }
    }
}
