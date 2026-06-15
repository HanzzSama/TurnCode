<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Onboarding routes - needs auth and verified email
Route::middleware(['auth', 'verified'])->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/interest', [OnboardingController::class, 'interest'])->name('interest');
    Route::post('/interest', [OnboardingController::class, 'storeInterest'])->name('interest.store');

    Route::get('/focus', [OnboardingController::class, 'focus'])->name('focus');
    Route::post('/focus', [OnboardingController::class, 'storeFocus'])->name('focus.store');

    Route::get('/welcome', [OnboardingController::class, 'welcome'])->name('welcome');
});

// App Routes (harus login, verify email, dan selesai onboarding)
Route::middleware(['auth', 'verified', 'onboarding'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $focus = $user->focus;

        $courseTitle = match ($focus) {
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

        $submateris = $userCourse ? $userCourse->submateris()->with('chapters.lessons')->get() : collect();
        $completedLessons = $user->lessons()->pluck('lesson_id')->toArray();

        $totalCourseLessons = 0;
        $completedCourseLessons = 0;

        foreach ($submateris as $sub) {
            foreach ($sub->chapters as $chap) {
                foreach ($chap->lessons as $lsn) {
                    $totalCourseLessons++;
                    if (in_array($lsn->id, $completedLessons)) {
                        $completedCourseLessons++;
                    }
                }
            }
        }

        $isCourseCompleted = $totalCourseLessons > 0 && $completedCourseLessons === $totalCourseLessons;

        $leaderboard = \App\Models\User::orderBy('exp', 'desc')->take(10)->get();
        $schedules = \App\Models\Schedule::where('user_id', $user->id)->get();
        $dailyMissions = \App\Services\MissionService::getUserDailyMissions($user);
        $currentSeason = \App\Models\Season::current();

        return view('dashboard', compact('userCourse', 'submateris', 'completedLessons', 'leaderboard', 'schedules', 'isCourseCompleted', 'dailyMissions', 'currentSeason'));
    })->name('dashboard');

    Route::get('/jadwal', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [\App\Http\Controllers\ScheduleController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{schedule}', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{schedule}', [\App\Http\Controllers\ScheduleController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/history', function () {
        $user = auth()->user();
        $completedLessons = $user->lessons()->with('chapter.submateri.course')->orderBy('lesson_user.created_at', 'desc')->get();

        $historyGroups = [];
        foreach ($completedLessons as $lesson) {
            $date = \Carbon\Carbon::parse($lesson->pivot->created_at);

            if ($date->isToday()) {
                $dateLabel = 'Hari ini';
            } elseif ($date->isYesterday()) {
                $dateLabel = 'Kemarin';
            } else {
                \Carbon\Carbon::setLocale('id');
                $dateLabel = $date->translatedFormat('d F Y');
            }

            $historyGroups[$dateLabel][] = $lesson;
        }

        $totalSessions = count($completedLessons);
        $totalExp = $user->exp ?? 0;
        $totalHours = round(($totalSessions * 30) / 60, 1); // Real calculation: 30 minutes per lesson

        // Heatmap Data (26 Weeks)
        $startDate = \Carbon\Carbon::now()->subWeeks(25)->startOfWeek(); // aligned to the start of the week
        $activities = \DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $heatmapData = [];
        for ($i = 0; $i < 26 * 7; $i++) {
            $currentDay = $startDate->copy()->addDays($i);
            $dateStr = $currentDay->format('Y-m-d');
            $count = $activities[$dateStr] ?? 0;

            if ($count == 0) {
                $level = '';
            } elseif ($count == 1) {
                $level = 'l1';
            } elseif ($count == 2) {
                $level = 'l2';
            } elseif ($count == 3) {
                $level = 'l3';
            } else {
                $level = 'l4';
            }

            \Carbon\Carbon::setLocale('id');
            $heatmapData[] = [
                'date' => $currentDay->translatedFormat('d M Y'),
                'count' => $count,
                'level' => $level
            ];
        }

        // Streak calculation
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

        return view('history', compact('historyGroups', 'totalSessions', 'totalExp', 'totalHours', 'streak', 'heatmapData'));
    })->name('history');

    Route::get('/courses/{course}', [LearningController::class, 'showCourse'])->name('courses.show');
    Route::get('/lessons/{lesson}', [LearningController::class, 'showLesson'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [LearningController::class, 'completeLesson'])->name('lessons.complete');
    Route::post('/lessons/{lesson}/quiz', [LearningController::class, 'submitQuiz'])->name('lessons.quiz.submit');

    Route::get('/certificates/submateri/{submateri}', [\App\Http\Controllers\CertificateController::class, 'generate'])->name('certificates.generate');

    // Exam Routes
    Route::get('/exam/agreement', [\App\Http\Controllers\ExamController::class, 'agreement'])->name('exam.agreement');
    Route::get('/exam/room', [\App\Http\Controllers\ExamController::class, 'room'])->name('exam.room');

    // API Routes
    Route::post('/api/user/add-exp', [UserController::class, 'addExp'])->name('api.user.addExp');
    Route::post('/api/user/buddy', [UserController::class, 'setBuddy'])->name('api.user.buddy');
    Route::get('/api/user/buddy-context', [UserController::class, 'getBuddyContext'])->name('api.user.buddyContext');
    Route::get('/api/friends', [UserController::class, 'getFriends'])->name('api.user.getFriends');
    Route::post('/api/friends/toggle', [UserController::class, 'toggleFriend'])->name('api.user.toggleFriend');
    Route::post('/api/friends/accept', [UserController::class, 'acceptRequest'])->name('api.user.acceptFriend');
    Route::post('/api/friends/decline', [UserController::class, 'declineRequest'])->name('api.user.declineFriend');
    Route::get('/api/friends/search', [UserController::class, 'searchFriends'])->name('api.user.searchFriends');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications API
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'fetchUnread'])->name('notifications.unread');
    Route::post('/api/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/api/notifications/delete-all', [\App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
    Route::post('/api/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// Admin Authentication & Workspace
Route::prefix('admin')->name('admin.')->group(function () {
    if (app()->environment('local')) {
        Route::get('/bypass-login', function() {
            session(['admin_authenticated' => true, 'admin_email' => 'admin@turncode.com']);
            return redirect()->route('admin.dashboard');
        });
    }
    // Guest Routes
    Route::get('/login', [\App\Http\Controllers\AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AdminAuthController::class, 'submitLogin'])->name('login.submit');
    Route::get('/otp', [\App\Http\Controllers\AdminAuthController::class, 'showOtp'])->name('otp');
    Route::post('/otp', [\App\Http\Controllers\AdminAuthController::class, 'submitOtp'])->name('otp.submit');
    Route::get('/recaptcha', [\App\Http\Controllers\AdminAuthController::class, 'showRecaptcha'])->name('recaptcha');
    Route::post('/recaptcha', [\App\Http\Controllers\AdminAuthController::class, 'submitRecaptcha'])->name('recaptcha.submit');

    // Secure Admin Workspace Routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/database/table-data/{table}', [\App\Http\Controllers\AdminDashboardController::class, 'getTableData'])->name('database.tableData');
        Route::put('/users/{user}/exp', [\App\Http\Controllers\AdminDashboardController::class, 'updateUserExp'])->name('users.updateExp');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminDashboardController::class, 'deleteUser'])->name('users.delete');

        // Interest CRUD
        Route::post('/interests', [\App\Http\Controllers\AdminDashboardController::class, 'storeInterest'])->name('interests.store');
        Route::put('/interests/{interest}', [\App\Http\Controllers\AdminDashboardController::class, 'updateInterest'])->name('interests.update');
        Route::delete('/interests/{interest}', [\App\Http\Controllers\AdminDashboardController::class, 'deleteInterest'])->name('interests.delete');

        // Fokus CRUD
        Route::post('/fokus', [\App\Http\Controllers\AdminDashboardController::class, 'storeFokus'])->name('fokus.store');
        Route::put('/fokus/{fokus}', [\App\Http\Controllers\AdminDashboardController::class, 'updateFokus'])->name('fokus.update');
        Route::delete('/fokus/{fokus}', [\App\Http\Controllers\AdminDashboardController::class, 'deleteFokus'])->name('fokus.delete');

        // Submateri CRUD
        Route::post('/submateri', [\App\Http\Controllers\AdminDashboardController::class, 'storeSubmateri'])->name('submateri.store');
        Route::put('/submateri/{submateri}', [\App\Http\Controllers\AdminDashboardController::class, 'updateSubmateri'])->name('submateri.update');
        Route::delete('/submateri/{submateri}', [\App\Http\Controllers\AdminDashboardController::class, 'deleteSubmateri'])->name('submateri.delete');
        Route::post('/media/upload', [\App\Http\Controllers\AdminDashboardController::class, 'uploadMedia'])->name('media.upload');

        // Quiz/Bank Soal CRUD
        Route::post('/quizzes', [\App\Http\Controllers\AdminDashboardController::class, 'storeQuiz'])->name('quizzes.store');
        Route::put('/quizzes/{quiz}', [\App\Http\Controllers\AdminDashboardController::class, 'updateQuiz'])->name('quizzes.update');
        Route::delete('/quizzes/{quiz}', [\App\Http\Controllers\AdminDashboardController::class, 'deleteQuiz'])->name('quizzes.delete');

        Route::post('/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');
    });
});

require __DIR__ . '/auth.php';

