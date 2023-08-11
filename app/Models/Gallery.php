<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'gallery';

    protected $fillable = [
        'user_id',
        'post_id',
        'image_url',
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function post() {
        return $this->belongsTo(Post::class,'post_id');
    }
}
