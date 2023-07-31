<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory,HasUuids;

    protected $table="user_profile";

    public $fillable = [
        'user_id',
        'gender',
        'dob',
        'cover_image_url',
        'introduce',
        'friends_count',
        'followers_count'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
