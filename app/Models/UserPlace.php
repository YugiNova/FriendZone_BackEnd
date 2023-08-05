<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlace extends Model
{
    use HasFactory,HasUuids;

    protected $table="user_place";

    public $fillable = [
        'user_id',
        'name',
        'type',
        'status'
    ];

    public $hidden = [
        'timestamps',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
