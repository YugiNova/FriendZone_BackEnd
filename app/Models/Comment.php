<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_slug',
        'slug',
        'content',
        'reactions_count',
        'replies_count'
    ];

    public function post() {
        return $this->belongsTo(Post::class,'post_id');
    }
    
    public function author() {
        return $this->belongsTo(User::class,'user_id');
    }
}
