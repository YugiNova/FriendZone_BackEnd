<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'send_id',
        'recieve_id',
        'content',
        'image_url',
    ];

    public function sendUser() {
        return $this->belongsTo(User::class,'send_id');
    }

    public function recieveUser() {
        return $this->belongsTo(User::class,'recieve_id');
    }
}
