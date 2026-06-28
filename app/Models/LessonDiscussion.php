<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonDiscussion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'likes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function parent()
    {
        return $this->belongsTo(LessonDiscussion::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LessonDiscussion::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get the likes array, ensuring it's always an array.
     */
    public function getLikesAttribute($value)
    {
        return json_decode($value ?? '[]', true) ?? [];
    }

    /**
     * Check if the given user liked this discussion.
     *
     * @param User|int|null $user
     * @return bool
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        $userId = $user instanceof User ? $user->id : (int) $user;
        return in_array($userId, $this->likes);
    }

    /**
     * Get total likes count.
     *
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return count($this->likes);
    }
}
