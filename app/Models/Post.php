<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory,HasUuids;

    protected $table="posts";

    protected $fillable = [
        'user_id',
        'content',
        'children_post_id',
        'reactions_count',
        'comments_count',
        'status',
        'score'
    ];

    public function author(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function childPost(){
        return $this->hasOne(Post::class,'children_post_id');
    }

    public function parentPost(){
        return $this->hasMany(Post::class,'children_post_id');
    }

    public function images(){
        return $this->hasMany(Gallery::class,'post_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class,'post_id');
    }

    public function rootComments(){
        return $this->comments()->where('parent_slug',null);
    }
}
