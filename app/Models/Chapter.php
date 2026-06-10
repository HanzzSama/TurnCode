<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $guarded = [];

    public function submateri()
    {
        return $this->belongsTo(Submateri::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
