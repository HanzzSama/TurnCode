<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fokus extends Model
{
    use HasFactory;

    protected $table = 'fokus';
    protected $guarded = [];

    public function interest()
    {
        return $this->belongsTo(Interest::class, 'interest_val', 'val');
    }
}
