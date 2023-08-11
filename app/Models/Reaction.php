<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'reactions';

    protected $fillable = [
        'user_id',
        'parent_id',
        'type'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
    public function parentPost() {
        return $this->belongsTo(Post::class,'post_id');
    }

    public function parentComment() {
        return $this->belongsTo(Comment::class,'comment_id');
    }
}
