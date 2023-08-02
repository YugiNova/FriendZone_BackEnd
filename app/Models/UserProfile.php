<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'followers_count',
    ];

    public $hidden = [
        'timestamps',
        'user_id',
        'id',
        'created_at',
        'updated_at'
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
