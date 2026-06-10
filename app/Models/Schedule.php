<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic',
        'course',
        'title',
        'description',
        'routine_type',
        'routine_config',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'routine_config' => 'array',
    ];
}
