<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContact extends Model
{
    use HasFactory,HasUuids;

    protected $table="user_contact";

    public $fillable = [
        'user_id',
        'content',
        'type',
        'status'
    ];

    public $hidden = [
        'timestamps',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
