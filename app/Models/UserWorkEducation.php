<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkEducation extends Model
{
    use HasFactory,HasUuids;

    protected $table="user_work_education";

    public $fillable = [
        'user_id',
        'name',
        'type',
        'year_start',
        "year_end"
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
