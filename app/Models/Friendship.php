<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory,HasUuids;

    protected $table="friendships";

    public $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    public $hidden = [
        'created_at',
        'updated_at'
    ];

    public function sendUser() {    
        return $this->belongsTo(User::class,'user_id');
    }

    public function recieveUser() {    
        return $this->belongsTo(User::class,'friend_id');
    }
}
