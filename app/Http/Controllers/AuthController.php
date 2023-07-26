<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\SendMail;
use App\Mail\VerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Respone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function App\Helper\custom_response;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = Auth::claims(['email' => $request->email])
                ->setTTL(60)
                ->attempt($credentials, true);

            if (!$token) {
                throw new Exception('Wrong email or password', 401);
            }
            return custom_response("Login successfull",$token);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
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
            return custom_response("Register successfull");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout(true);

            return custom_response("Log out successfull");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function getData(Request $request)
    {
        try {
            // $token = $request->get('token');
            $data = Auth::getPayload();

            return custom_response("Get data successfull");
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

            return custom_response("Verify email send successfull");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function verifyMail(Request $request,$id,$token){
        try {
            $user = User::find($id);
            $user->markEmailAsVerified();
            // return custom_response("Email is verified");
            return redirect("https://friendzone.yuginova.vercel.app/");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function sendResetPasswordMail(Request $request){
        try {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email',"=",$request->email)->first();
            if(!$user){
                throw new Exception("Email not found");
            }

            $url = route('auth.password.reset.link', ['id' => $user['id']]);
            Mail::to($user['email'])->send(new VerifyEmail($url, $user['display_name']));

            return custom_response("Send reset password email successfull",$url);
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }

    public function resetPassword(Request $request,$id){
        try {
            $user = User::find($id);
            
            return custom_response("Email is verified");
            // return redirect("https://friendzone.yuginova.vercel.app/");
        } catch (\Exception $e) {
            throw new AuthException($e);
        }
    }
}
