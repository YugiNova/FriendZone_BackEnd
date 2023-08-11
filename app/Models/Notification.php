<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'notifications';

    protected $fillable = [
        'send_id',
        'recieve_id',
        'type',
        'source_id',
        'content',
        'is_read'
    ];

    public function sendUser(){
        return $this->belongsTo(User::class,'send_id');
    }

    public function recieveUser(){
        return $this->belongsTo(User::class,'recieve_id');
    }
}
