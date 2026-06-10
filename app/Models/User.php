<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'interest', 'focus', 'onboarding_completed', 'exp', 'buddy_avatar', 'buddy_name', 'achievements'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarding_completed' => 'boolean',
            'achievements' => 'array',
        ];
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    public function pendingFriendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function pendingFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function isFriendOf($user)
    {
        return $this->friends()->where('friend_id', is_numeric($user) ? $user : $user->id)->exists();
    }

    public function friendshipStatusWith($user)
    {
        $userId = is_numeric($user) ? $user : $user->id;
        
        $friendship = \DB::table('friendships')
            ->where(function($query) use ($userId) {
                $query->where('user_id', $this->id)->where('friend_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('user_id', $userId)->where('friend_id', $this->id);
            })
            ->first();

        if (!$friendship) {
            return 'none';
        }

        if ($friendship->status === 'accepted') {
            return 'friends';
        }

        if ($friendship->user_id == $this->id) {
            return 'pending_sent';
        }

        return 'pending_received';
    }

    public function getLevelAttribute()
    {
        $exp = $this->exp ?? 0;
        return min(200, floor(sqrt($exp / 100)) + 1);
    }

    public function getTierAttribute()
    {
        $level = $this->level;
        
        if ($level >= 196) return 'Visionary';
        if ($level >= 171) return 'Strategist';
        if ($level >= 141) return 'Principal';
        if ($level >= 111) return 'Architect';
        if ($level >= 81) return 'Lead Engineer';
        if ($level >= 56) return 'Senior Professional';
        if ($level >= 36) return 'Professional';
        if ($level >= 21) return 'Specialist';
        if ($level >= 11) return 'Technician';
        if ($level >= 6) return 'Operator';
        if ($level >= 2) return 'Explorer';
        return 'Initiate';
    }

    public function getNextTierExpAttribute()
    {
        $level = $this->level;
        
        if ($level >= 196) return 3960100; // max level 200 exp
        if ($level >= 171) return 3802500; // visionary starting exp (level 196)
        if ($level >= 141) return 2890000; // strategist starting exp (level 171)
        if ($level >= 111) return 1960000; // principal starting exp (level 141)
        if ($level >= 81) return 1210000;  // architect starting exp (level 111)
        if ($level >= 56) return 640000;   // lead engineer starting exp (level 81)
        if ($level >= 36) return 302500;   // senior professional starting exp (level 56)
        if ($level >= 21) return 122500;   // professional starting exp (level 36)
        if ($level >= 11) return 40000;    // specialist starting exp (level 21)
        if ($level >= 6) return 10000;     // technician starting exp (level 11)
        if ($level >= 2) return 2500;      // operator starting exp (level 6)
        return 100;                        // explorer starting exp (level 2)
    }

    public function getExpPercentageAttribute()
    {
        $exp = $this->exp ?? 0;
        $level = $this->level;
        
        if ($level >= 200) return 100;
        
        $floorExp = 0;
        $nextExp = $this->next_tier_exp;
        
        if ($level >= 196) $floorExp = 3802500;
        elseif ($level >= 171) $floorExp = 2890000;
        elseif ($level >= 141) $floorExp = 1960000;
        elseif ($level >= 111) $floorExp = 1210000;
        elseif ($level >= 81) $floorExp = 640000;
        elseif ($level >= 56) $floorExp = 302500;
        elseif ($level >= 36) $floorExp = 122500;
        elseif ($level >= 21) $floorExp = 40000;
        elseif ($level >= 11) $floorExp = 10000;
        elseif ($level >= 6) $floorExp = 2500;
        elseif ($level >= 2) $floorExp = 100;
        else $floorExp = 0;
        
        $range = $nextExp - $floorExp;
        if ($range <= 0) return 100;
        
        $progress = $exp - $floorExp;
        return min(100, max(0, round(($progress / $range) * 100)));
    }
}
