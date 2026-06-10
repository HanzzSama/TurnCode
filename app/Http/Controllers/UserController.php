<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class UserController extends Controller
{
    public function addExp(Request $request)
    {
        $user = $request->user();
        $oldTier = $user->tier;
        $user->exp += 5;
        $user->save();
        $newTier = $user->tier;

        if ($oldTier !== $newTier) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Naik Tier! 🏆',
                'description' => "Selamat! Kamu telah naik ke tier '{$newTier}' dengan total {$user->exp} EXP. Teruskan perjuanganmu!",
                'type' => 'learning',
            ]);
        }

        $tierColors = [
            'Initiate' => '168, 162, 158',
            'Explorer' => '34, 197, 94',
            'Operator' => '59, 130, 246',
            'Technician' => '139, 92, 246',
            'Specialist' => '236, 72, 153',
            'Professional' => '239, 68, 68',
            'Senior Professional' => '249, 115, 22',
            'Lead Engineer' => '234, 179, 8',
            'Architect' => '6, 182, 212',
            'Principal' => '15, 118, 110',
            'Strategist' => '225, 29, 72',
            'Visionary' => '218, 165, 32',
        ];

        return response()->json([
            'success' => true,
            'exp' => $user->exp,
            'level' => $user->level,
            'tier' => $user->tier,
            'next_tier_exp' => $user->next_tier_exp,
            'exp_percentage' => $user->exp_percentage,
            'tier_changed' => $oldTier !== $newTier,
            'old_tier' => $oldTier,
            'new_tier' => $newTier,
            'tier_color' => $tierColors[$newTier] ?? '168, 162, 158',
            'old_tier_color' => $tierColors[$oldTier] ?? '168, 162, 158',
        ]);
    }

    public function setBuddy(Request $request)
    {
        $request->validate([
            'buddy_avatar' => 'required|string',
            'buddy_name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->buddy_avatar = $request->buddy_avatar;
        $user->buddy_name = $request->buddy_name;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Buddy set successfully',
            'buddy_avatar' => $user->buddy_avatar,
            'buddy_name' => $user->buddy_name,
        ]);
    }

    public function getBuddyContext(Request $request)
    {
        $user = $request->user();
        
        // Calculate Streak
        $activityDates = \DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->select(\DB::raw('DATE(created_at) as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        $streak = 0;
        if (!empty($activityDates)) {
            $today = \Carbon\Carbon::today()->format('Y-m-d');
            $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
            
            $currentDate = null;
            if (in_array($today, $activityDates)) {
                $currentDate = \Carbon\Carbon::today();
            } elseif (in_array($yesterday, $activityDates)) {
                $currentDate = \Carbon\Carbon::yesterday();
            }
            
            if ($currentDate) {
                while (true) {
                    $dateStr = $currentDate->format('Y-m-d');
                    if (in_array($dateStr, $activityDates)) {
                        $streak++;
                        $currentDate->subDay();
                    } else {
                        break;
                    }
                }
            }
        }

        // Daily Missions progress
        $dailyMissions = \App\Services\MissionService::getUserDailyMissions($user);
        $totalMissions = count($dailyMissions);
        $completedMissions = 0;
        foreach ($dailyMissions as $mission) {
            if ($mission['progress'] >= $mission['target']) {
                $completedMissions++;
            }
        }

        // Active Course & Lesson Recommendation
        $focus = $user->focus;
        $courseTitle = match($focus) {
            'frontend' => 'Front End',
            'backend' => 'Back End',
            'fullstack' => 'Full Stack Dev',
            'data-analyst' => 'Data Analyze',
            default => 'Front End'
        };

        $userCourse = \App\Models\Course::where('title', 'like', "%$courseTitle%")->first();
        if (!$userCourse) {
            $userCourse = \App\Models\Course::first();
        }

        $recommendation = [
            'has_recommendation' => false,
            'course_id' => null,
            'course_title' => null,
            'lesson_id' => null,
            'lesson_title' => null,
            'progress_percent' => 0
        ];

        if ($userCourse) {
            $userCourse->load(['submateris.chapters.lessons']);
            $completedLessons = $user->lessons()->pluck('lesson_id')->toArray();
            
            $totalCourseLessons = 0;
            $completedCourseLessons = 0;
            $nextLesson = null;
            
            foreach ($userCourse->submateris as $submateri) {
                foreach ($submateri->chapters as $chapter) {
                    foreach ($chapter->lessons as $lsn) {
                        $totalCourseLessons++;
                        if (in_array($lsn->id, $completedLessons)) {
                            $completedCourseLessons++;
                        } else {
                            if (!$nextLesson) {
                                $nextLesson = $lsn;
                            }
                        }
                    }
                }
            }
            
            $progressPercent = $totalCourseLessons > 0 ? min(100, round(($completedCourseLessons / $totalCourseLessons) * 100)) : 0;
            
            $recommendation = [
                'has_recommendation' => $nextLesson !== null,
                'course_id' => $userCourse->id,
                'course_title' => $userCourse->title,
                'lesson_id' => $nextLesson ? $nextLesson->id : null,
                'lesson_title' => $nextLesson ? $nextLesson->title : null,
                'progress_percent' => $progressPercent
            ];
        }

        return response()->json([
            'success' => true,
            'user_name' => $user->name,
            'buddy_name' => $user->buddy_name,
            'buddy_avatar' => $user->buddy_avatar,
            'level' => $user->level,
            'tier' => $user->tier,
            'exp' => $user->exp,
            'exp_percentage' => $user->exp_percentage,
            'streak' => $streak,
            'missions_total' => $totalMissions,
            'missions_done' => $completedMissions,
            'hour_of_day' => (int) date('G'), // 0-23
            'recommendation' => $recommendation,
        ]);
    }

    public function getFriends(Request $request)
    {
        $user = $request->user();
        $friends = $user->friends()->orderBy('exp', 'desc')->get()->map(function ($friend) {
            return [
                'id' => $friend->id,
                'name' => $friend->name,
                'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($friend->name) . "&background=random",
                'exp' => $friend->exp,
                'level' => $friend->level,
                'tier' => $friend->tier,
            ];
        });

        $requests = $user->pendingFriendRequests()->get()->map(function ($req) {
            return [
                'id' => $req->id,
                'name' => $req->name,
                'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($req->name) . "&background=random",
                'exp' => $req->exp,
                'level' => $req->level,
                'tier' => $req->tier,
            ];
        });

        return response()->json([
            'success' => true,
            'friends' => $friends,
            'requests' => $requests,
        ]);
    }

    public function toggleFriend(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|integer|exists:users,id',
        ]);

        $user = $request->user();
        $friendId = (int) $request->friend_id;

        if ($user->id === $friendId) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa berteman dengan diri sendiri.',
            ], 400);
        }

        $status = $user->friendshipStatusWith($friendId);

        if ($status === 'friends') {
            \DB::table('friendships')
                ->where(function($query) use ($user, $friendId) {
                    $query->where('user_id', $user->id)->where('friend_id', $friendId);
                })
                ->orWhere(function($query) use ($user, $friendId) {
                    $query->where('user_id', $friendId)->where('friend_id', $user->id);
                })
                ->delete();

            $action = 'removed';
            $message = 'Berhasil menghapus pertemanan.';
        } elseif ($status === 'pending_sent') {
            \DB::table('friendships')
                ->where('user_id', $user->id)
                ->where('friend_id', $friendId)
                ->where('status', 'pending')
                ->delete();

            $action = 'cancelled';
            $message = 'Permintaan pertemanan dibatalkan.';
        } elseif ($status === 'pending_received') {
            \DB::transaction(function () use ($user, $friendId) {
                \DB::table('friendships')
                    ->where('user_id', $friendId)
                    ->where('friend_id', $user->id)
                    ->update([
                        'status' => 'accepted',
                        'updated_at' => now(),
                    ]);

                \DB::table('friendships')->updateOrInsert(
                    ['user_id' => $user->id, 'friend_id' => $friendId],
                    ['status' => 'accepted', 'created_at' => now(), 'updated_at' => now()]
                );
            });

            $action = 'accepted';
            $message = 'Berhasil menerima permintaan pertemanan.';

            Notification::create([
                'user_id' => $friendId,
                'title' => 'Permintaan Teman Diterima! 👥',
                'description' => "{$user->name} telah menerima permintaan pertemananmu. Ayo saling kejar rank quota!",
                'type' => 'profile',
            ]);
        } else {
            \DB::table('friendships')->insert([
                'user_id' => $user->id,
                'friend_id' => $friendId,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $action = 'requested';
            $message = 'Permintaan pertemanan dikirim.';

            Notification::create([
                'user_id' => $friendId,
                'title' => 'Permintaan Pertemanan! 👥',
                'description' => "{$user->name} ingin menambahkanmu sebagai teman.",
                'type' => 'profile',
            ]);
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'status' => $user->friendshipStatusWith($friendId),
            'message' => $message,
            'friends_count' => $user->friends()->count(),
        ]);
    }

    public function acceptRequest(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|integer|exists:users,id',
        ]);

        $user = $request->user();
        $friendId = (int) $request->friend_id;

        $pendingExists = \DB::table('friendships')
            ->where('user_id', $friendId)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if (!$pendingExists) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada permintaan pertemanan yang tertunda dari pengguna ini.',
            ], 400);
        }

        \DB::transaction(function () use ($user, $friendId) {
            \DB::table('friendships')
                ->where('user_id', $friendId)
                ->where('friend_id', $user->id)
                ->update([
                    'status' => 'accepted',
                    'updated_at' => now(),
                ]);

            \DB::table('friendships')->updateOrInsert(
                ['user_id' => $user->id, 'friend_id' => $friendId],
                ['status' => 'accepted', 'created_at' => now(), 'updated_at' => now()]
            );
        });

        Notification::create([
            'user_id' => $friendId,
            'title' => 'Permintaan Teman Diterima! 👥',
            'description' => "{$user->name} telah menerima permintaan pertemananmu. Ayo saling kejar rank quota!",
            'type' => 'profile',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pertemanan diterima.',
            'friends_count' => $user->friends()->count(),
        ]);
    }

    public function declineRequest(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|integer|exists:users,id',
        ]);

        $user = $request->user();
        $friendId = (int) $request->friend_id;

        \DB::table('friendships')
            ->where(function($query) use ($user, $friendId) {
                $query->where('user_id', $friendId)->where('friend_id', $user->id);
            })
            ->orWhere(function($query) use ($user, $friendId) {
                $query->where('user_id', $user->id)->where('friend_id', $friendId);
            })
            ->where('status', 'pending')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan pertemanan ditolak/dibatalkan.',
            'friends_count' => $user->friends()->count(),
        ]);
    }

    public function searchFriends(Request $request)
    {
        $user = $request->user();
        $query = $request->query('query', '');

        $users = \App\Models\User::where('id', '!=', $user->id)
            ->where('name', 'like', "%{$query}%")
            ->orderBy('exp', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($u) use ($user) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($u->name) . "&background=random",
                    'exp' => $u->exp,
                    'level' => $u->level,
                    'tier' => $u->tier,
                    'is_friend' => $user->isFriendOf($u),
                    'friendship_status' => $user->friendshipStatusWith($u),
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }
}
