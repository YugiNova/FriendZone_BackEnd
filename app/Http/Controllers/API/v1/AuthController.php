<?php

namespace App\Http\Controllers\API\v1;

use App\Exceptions\AuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\MyHelper;

class AuthController extends Controller
{

    private $hepler;
    public function __construct(MyHelper $hepler)
    {
        $this->hepler = $hepler;
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $token = Auth::claims(['email' => $request->email])
                ->setTTL(60)
                ->attempt($credentials, true);
            
            if(!$token){
                throw new Exception("Email or password was wrong");
            }

            return $this->hepler->custom_response("Login successfull",null,$token);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function checkLogin(Request $request){
        try {
            $user = Auth::user();
            $userDB = User::find($user['id']);
            $userProfile = $userDB->profile;
    
            return $this->hepler->custom_response("User is already login",$userDB);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'display_name' => $request->display_name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar_url' => $request->avatar_url ?? null,
                'status' => 'active',
            ]);
            $userProfile = UserProfile::create([
                'user_id' => $user->id,
                'gender' => $request->gender,
                'dob' => $request->dob,
                
            ]);
            $slug = $user->update(['slug'=>$userProfile->id]);
            DB::commit();
            return $this->hepler->custom_response("Register successfull");
        } catch (\Exception $e) {
            DB::rollback();
            throw new AuthException($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout(true);

            return $this->hepler->custom_response("Log out successfull")->withoutCookie('token');
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function sendVerificationMail(Request $request)
    {
        try {
            $token = $request->cookie('token');
            $user = Auth::user();
            $url = route('auth.email.verify.link', ['id' => $user['id'], 'token' => $token]);
            Mail::to($user['email'])->send(new VerifyEmail($url, $user['display_name']));

            return $this->hepler->custom_response("Verify email send successfull");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function verifyMail(Request $request,$id,$token)
    {
        try {
            $token = Auth::getPayload();
            if(!$token){
                throw new Exception("Token is invalid");
            }
            $user = User::find($id);
            
            $user->markEmailAsVerified();
            // return custom_response("Email is verified");
            return redirect(env("FRONTEND_URL"));
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function findEmail(Request $request)
    {
        try {
            $user = User::where('email',"=",$request->email)->first();
            if(!$user){
                throw new Exception("Email not found");
            }
            return $this->hepler->custom_response("Email was found", $user);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function sendResetPasswordMail(Request $request)
    {
        try {
            //Validate email
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            //Check email in database
            $user = User::where('email',"=",$request->email)->first();
            if(!$user){
                throw new Exception("Email not found");
            }

            //Find email in table password_reset_tokens in database
            $checkEmail = DB::table('password_reset_tokens')->where('email',$request->email)->get();
            if(!$checkEmail->isEmpty()){
                DB::table('password_reset_tokens')->where('email',$request->email)->delete();
            }

            //Return password_reset_token by hash email and JWT_SECRET
            $token = Str::uuid()->toString();

            //Add token to table password_reset_tokens in database
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token
            ]);

            $url = route('auth.password.reset.link',['id'=>$user['id'],'token'=>$token]);
            Mail::to($user['email'])->send(new ResetPassword($url, $user['display_name']));

            return $this->hepler->custom_response("Send reset password email successfull",$url);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function resetPassword(Request $request,$id,$token)
    {
        try {
            $user = User::find($id);
            $email = $user['email'];

            $check = DB::table('password_reset_tokens')
                    ->where('email',$email)
                    ->where('token',$token)
                    ->get();

            if(!$check){
                throw new Exception("Token or User is invalid");
            }
            
            return redirect(env('FRONTEND_URL')."/update-password/$id/$token");
            // return redirect(env('FRONTEND_URL'));
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function updatePassword(ResetPasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find($request->id);

            if(!$user){
                throw new Exception("User id is invalid");
            }

            $email = $user['email'];
            $token = $request->token;

            $check = DB::table('password_reset_tokens')
                    ->where('email',$email)
                    ->where('token',$token)
                    ->get();

            if(!$check){
                throw new Exception("Token is invalid");
            }

            $user->update(['password'=>Hash::make($request->password)]);

            $check = DB::table('password_reset_tokens')
                    ->where('email',$email)
                    ->orWhere('token',$token)
                    ->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new AuthException($e);
        }
    }

    public function removeCookie(Request $request)
    {
        return $this->hepler->custom_response("Remove user cookie")->withoutCookie('token');
    }
}
