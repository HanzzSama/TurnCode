<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailyMission extends Model
{
    protected $fillable = [
        'user_id',
        'mission_key',
        'progress',
        'is_completed',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
