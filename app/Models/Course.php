<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function submateris()
    {
        return $this->hasMany(Submateri::class)->orderBy('order');
    }

    public function chapters()
    {
        return $this->hasManyThrough(Chapter::class, Submateri::class);
    }
}
