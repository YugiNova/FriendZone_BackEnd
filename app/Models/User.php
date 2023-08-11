<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes,HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'display_name',
        'nickname',
        'email',
        'password',
        'avatar_url',
        'role',
        'status',
        'slug'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role',
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    // User Profile
    public function profile():HasOne{
        return $this->hasOne(UserProfile::class,'user_id');
    }

    public function contacts(){
        return $this->hasMany(UserContact::class,'user_id');
    }

    public function works_educations(){
        return $this->hasMany(UserWorkEducation::class,'user_id');
    }

    public function places(){
        return $this->hasMany(UserPlace::class,'user_id');
    }

    public function friends(){
        return $this->belongsToMany(User::class,'friendships','user_id','friend_id');
                    // ->withPivot('status')
                    // ->withTimestamps();
    }


    // User friendships
    public function friendsOf(){
        return $this->belongsToMany(User::class,'friendships','friend_id','user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function friendsList(){
        return $this->friends()->wherePivot('status','friend');
    }

    public function friendsRequestList(){
        return $this->friends()->wherePivot('status','request');
    }

    //User Activities
    public function posts() {
        return $this->hasMany(Post::class,'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class,'user_id');
    }

    public function reactions(){
        return $this->hasMany(Reaction::class,'user_id');
    }

    public function sentNotifications() {
        return $this->hasMany(Notification::class,'send_id');
    }

    public function recievedNotifications() {
        return $this->hasMany(Notification::class,'recieve_id');
    }
}
