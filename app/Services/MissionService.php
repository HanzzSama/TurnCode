<?php

namespace App\Services;

use App\Models\UserDailyMission;
use App\Models\Notification;
use Carbon\Carbon;

class MissionService
{
    /**
     * Define the static daily missions here.
     */
    public static function getMissions()
    {
        return [
            'read_1_lesson' => [
                'name' => 'Baca 1 SubMateri',
                'type' => 'read_lesson',
                'target_count' => 1,
                'reward_exp' => 200,
            ],
            'finish_1_quiz' => [
                'name' => 'Selesaikan 1 Quiz',
                'type' => 'finish_quiz',
                'target_count' => 1,
                'reward_exp' => 350,
            ],
            'read_3_lesson' => [
                'name' => 'Baca 3 SubMateri',
                'type' => 'read_lesson',
                'target_count' => 3,
                'reward_exp' => 350,
            ],
        ];
    }

    /**
     * Get the user's daily missions with their progress.
     */
    public static function getUserDailyMissions($user)
    {
        $today = Carbon::today()->toDateString();
        $missions = self::getMissions();
        
        $userMissions = UserDailyMission::where('user_id', $user->id)
            ->where('date', $today)
            ->get()
            ->keyBy('mission_key');

        $result = [];
        foreach ($missions as $key => $mission) {
            $userMission = $userMissions->get($key);
            
            $progress = $userMission ? $userMission->progress : 0;
            $isCompleted = $userMission ? $userMission->is_completed : false;

            $result[] = [
                'key' => $key,
                'name' => $mission['name'],
                'target' => $mission['target_count'],
                'reward_exp' => $mission['reward_exp'],
                'progress' => min($progress, $mission['target_count']),
                'is_completed' => $isCompleted,
            ];
        }

        return $result;
    }

    /**
     * Update progress for a specific mission type.
     */
    public static function updateProgress($user, $type, $amount = 1)
    {
        $today = Carbon::today()->toDateString();
        $missions = self::getMissions();

        foreach ($missions as $key => $mission) {
            if ($mission['type'] === $type) {
                // Find or create the user's daily mission record for today
                $userMission = UserDailyMission::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'mission_key' => $key,
                        'date' => $today,
                    ],
                    [
                        'progress' => 0,
                        'is_completed' => false,
                    ]
                );

                if (!$userMission->is_completed) {
                    $userMission->progress += $amount;

                    if ($userMission->progress >= $mission['target_count']) {
                        $userMission->is_completed = true;
                        
                        // Auto-claim reward
                        $user->exp += $mission['reward_exp'];
                        $user->save();

                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'Misi Harian Selesai! 🎉',
                            'description' => "Kamu telah menyelesaikan misi '{$mission['name']}' dan mendapatkan {$mission['reward_exp']} EXP.",
                            'type' => 'learning',
                        ]);
                    }

                    $userMission->save();
                }
            }
        }
    }
}
