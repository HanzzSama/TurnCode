<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Season extends Model
{
    protected $fillable = [
        'number',
        'name',
        'starts_at',
        'ends_at',
        'is_active',
        'rewards_distributed',
        'leaderboard_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'rewards_distributed' => 'boolean',
            'leaderboard_snapshot' => 'array',
        ];
    }

    /**
     * Get the currently active season, or create/transition one if needed.
     */
    public static function current(): self
    {
        $now = Carbon::now();

        // 1. Try to find an active season that hasn't expired
        $active = static::where('is_active', true)
            ->where('ends_at', '>', $now)
            ->first();

        if ($active) {
            return $active;
        }

        // 2. If there's an expired active season, end it and distribute rewards
        $expired = static::where('is_active', true)
            ->where('ends_at', '<=', $now)
            ->first();

        if ($expired) {
            static::endSeason($expired);
        }

        // 3. Create the next season
        return static::createNextSeason();
    }

    /**
     * End a season: snapshot leaderboard, distribute achievements, deactivate.
     */
    public static function endSeason(self $season): void
    {
        if ($season->rewards_distributed) {
            $season->update(['is_active' => false]);
            return;
        }

        // Snapshot the top 10 leaderboard
        $topUsers = User::orderBy('exp', 'desc')->take(10)->get();
        $snapshot = $topUsers->map(fn($u, $i) => [
            'rank' => $i + 1,
            'user_id' => $u->id,
            'name' => $u->name,
            'exp' => $u->exp,
        ])->toArray();

        // Distribute achievements to participants
        static::distributeSeasonAchievements($season, $topUsers);

        $season->update([
            'is_active' => false,
            'rewards_distributed' => true,
            'leaderboard_snapshot' => $snapshot,
        ]);
    }

    /**
     * Create the next season (monthly, from 1st to end of month).
     */
    public static function createNextSeason(): self
    {
        $now = Carbon::now();
        $lastSeason = static::orderBy('number', 'desc')->first();
        $nextNumber = $lastSeason ? $lastSeason->number + 1 : 1;

        return static::create([
            'number' => $nextNumber,
            'name' => "Season $nextNumber",
            'starts_at' => $now->copy()->startOfMonth(),
            'ends_at' => $now->copy()->endOfMonth(),
            'is_active' => true,
            'rewards_distributed' => false,
        ]);
    }

    /**
     * Distribute achievements based on season ranking.
     */
    protected static function distributeSeasonAchievements(self $season, $topUsers): void
    {
        $seasonName = $season->name;

        // All users who have any EXP are "participants"
        $allParticipants = User::where('exp', '>', 0)->get();

        foreach ($allParticipants as $user) {
            $achievements = $user->achievements ?? [];
            $existingTitles = array_column($achievements, 'title');

            $newAchievements = [];

            // Check if user is in top 3
            $rank = null;
            foreach ($topUsers as $index => $topUser) {
                if ($topUser->id === $user->id) {
                    $rank = $index + 1;
                    break;
                }
            }

            if ($rank === 1) {
                $title = "Top 1 $seasonName";
                if (!in_array($title, $existingTitles)) {
                    $newAchievements[] = ['title' => $title, 'icon' => '🏆', 'color' => '#ffd700'];
                }
            } elseif ($rank === 2) {
                $title = "Top 2 $seasonName";
                if (!in_array($title, $existingTitles)) {
                    $newAchievements[] = ['title' => $title, 'icon' => '🥈', 'color' => '#c0c0c0'];
                }
            } elseif ($rank === 3) {
                $title = "Top 3 $seasonName";
                if (!in_array($title, $existingTitles)) {
                    $newAchievements[] = ['title' => $title, 'icon' => '🥉', 'color' => '#cd7f32'];
                }
            }

            // "Pejuang" achievement for all participants
            $pejuangTitle = "Pejuang $seasonName";
            if (!in_array($pejuangTitle, $existingTitles)) {
                $newAchievements[] = ['title' => $pejuangTitle, 'icon' => '⚔️', 'color' => '#8a2be2'];
            }

            if (!empty($newAchievements)) {
                $user->achievements = array_merge($achievements, $newAchievements);
                $user->save();
            }
        }
    }
}
