<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Interest;
use App\Models\Fokus;
use App\Models\Course;
use App\Models\Submateri;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $totalUsers = $users->count();
        $totalExp = $users->sum('exp');
        
        $avgLevel = 1;
        if ($totalUsers > 0) {
            $avgLevel = round($users->avg('level'), 1);
        }

        // Course & Chapter counts
        $totalCourses = Course::count();
        $totalChapters = Chapter::count();

        // Interest statistics: count users per interest
        $interestStats = Interest::all()->map(function ($interest) {
            return [
                'name' => $interest->name,
                'val'  => $interest->val,
                'count' => User::where('interest', $interest->val)->count(),
            ];
        })->sortByDesc('count')->values();

        // All interests & fokus for management tabs
        $interests = Interest::orderBy('name')->get();
        $fokusList = Fokus::orderBy('interest_val')->orderBy('name')->get();
        
        // Newly added lists for Submateri and Quiz tabs
        $submateris = Submateri::with('course')->orderBy('course_id')->orderBy('order')->get();
        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::orderBy('title')->get();
        $quizzes = Quiz::with('lesson.chapter.submateri.course')->orderBy('id', 'desc')->get();

        // Leaderboard / Paginated users list for the table
        $paginatedUsers = User::orderBy('exp', 'desc')->paginate(10);

        // Current active tab (for SPA-style navigation)
        $activeTab = $request->query('tab', 'dashboard');

        // Dynamic DB tables stats: fetch all tables in the database dynamically
        $rawTables = \Illuminate\Support\Facades\Schema::getTables();
        $dbTables = [];
        $tablesDetail = [];

        foreach ($rawTables as $table) {
            $tableName = $table['name'];
            
            try {
                $count = \Illuminate\Support\Facades\DB::table($tableName)->count();
            } catch (\Throwable $e) {
                // Skip broken tables or views that don't exist
                continue;
            }
            
            $desc = match($tableName) {
                'users' => 'Tabel data akun pengguna',
                'interests' => 'Tabel kategori peminatan utama',
                'fokus' => 'Tabel materi fokus spesialisasi',
                'courses' => 'Tabel data modul utama (Course)',
                'submateris' => 'Tabel sub-materi pembelajaran',
                'chapters' => 'Tabel bab pembagian materi',
                'lessons' => 'Tabel sesi belajar / teks materi',
                'quizzes' => 'Tabel bank soal kuis pembelajaran',
                'schedules' => 'Tabel jadwal belajar terencana',
                'notifications' => 'Tabel log notifikasi sistem',
                'migrations' => 'Tabel riwayat migrasi database',
                'failed_jobs' => 'Tabel log antrean proses gagal',
                'password_reset_tokens' => 'Tabel token reset password',
                'personal_access_tokens' => 'Tabel token akses API personal',
                'sessions' => 'Tabel sesi aktif pengguna',
                'lesson_user' => 'Tabel pivot penyelesaian materi oleh user',
                default => 'Tabel sistem database (' . $tableName . ')'
            };

            $dbTables[] = [
                'name' => $tableName,
                'desc' => $desc,
                'count' => $count
            ];

            // Get columns and indexes dynamically
            $columns = \Illuminate\Support\Facades\Schema::getColumns($tableName);
            
            $primaryCols = [];
            $uniqueCols = [];
            try {
                $indexes = \Illuminate\Support\Facades\Schema::getIndexes($tableName);
                foreach ($indexes as $idx) {
                    if (!empty($idx['primary'])) {
                        $primaryCols = array_merge($primaryCols, $idx['columns']);
                    } elseif (!empty($idx['unique'])) {
                        $uniqueCols = array_merge($uniqueCols, $idx['columns']);
                    }
                }
            } catch (\Throwable $e) {
                // Fallback if getIndexes is not supported or fails
            }

            $colsData = [];
            foreach ($columns as $col) {
                $key = '';
                $isPrimary = (isset($col['primary']) && $col['primary']) || in_array($col['name'], $primaryCols) || ($col['name'] === 'id');
                $isUnique = (isset($col['unique']) && $col['unique']) || in_array($col['name'], $uniqueCols);

                if ($isPrimary) {
                    $key = 'primary';
                } elseif ($isUnique) {
                    $key = 'unique';
                }

                if (str_ends_with($col['name'], '_id') && $col['name'] !== 'id') {
                    $key = 'foreign';
                    $targetTable = str_replace('_id', '', $col['name']);
                    
                    if ($targetTable === 'submateri') {
                        $targetTable = 'submateris';
                    } elseif ($targetTable === 'fokus') {
                        $targetTable = 'fokus';
                    } elseif ($targetTable === 'user') {
                        $targetTable = 'users';
                    } elseif ($targetTable === 'chapter') {
                        $targetTable = 'chapters';
                    } elseif ($targetTable === 'lesson') {
                        $targetTable = 'lessons';
                    } elseif ($targetTable === 'course') {
                        $targetTable = 'courses';
                    } elseif ($targetTable === 'interest') {
                        $targetTable = 'interests';
                    } else {
                        $targetTable = \Illuminate\Support\Str::plural($targetTable);
                    }
                    
                    $colsData[] = [
                        'name' => $col['name'],
                        'type' => $col['type_name'],
                        'key' => 'foreign',
                        'ref' => $targetTable . '.id'
                    ];
                } else {
                    $colsData[] = [
                        'name' => $col['name'],
                        'type' => $col['type_name'],
                        'key' => $key
                    ];
                }
            }

            $tablesDetail[$tableName] = [
                'title' => $tableName,
                'desc' => $desc,
                'count' => $count,
                'columns' => $colsData
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExp',
            'avgLevel',
            'totalCourses',
            'totalChapters',
            'interestStats',
            'interests',
            'fokusList',
            'submateris',
            'courses',
            'lessons',
            'quizzes',
            'paginatedUsers',
            'activeTab',
            'dbTables',
            'tablesDetail'
        ));
    }

    public function updateUserExp(Request $request, User $user)
    {
        $request->validate([
            'exp' => 'required|integer|min:0'
        ]);

        $user->exp = $request->exp;
        $user->save();

        return redirect()->route('admin.dashboard', ['tab' => 'fitur'])->with('success', "EXP untuk {$user->name} berhasil diperbarui menjadi {$request->exp}.");
    }

    public function deleteUser(User $user)
    {
        // Don't allow deleting yourself
        if (session('admin_email') === $user->email) {
            return redirect()->route('admin.dashboard', ['tab' => 'fitur'])->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'fitur'])->with('success', "Pengguna {$user->name} berhasil dihapus dari sistem.");
    }

    // ─── INTEREST CRUD ───

    public function storeInterest(Request $request)
    {
        $request->validate([
            'val'  => 'required|string|max:50|unique:interests,val',
            'name' => 'required|string|max:100',
            'desc' => 'nullable|string|max:255',
            'icon' => 'nullable|string',
        ]);

        Interest::create($request->only(['val', 'name', 'desc', 'icon']));

        return redirect()->route('admin.dashboard', ['tab' => 'interests'])->with('success', "Interest \"{$request->name}\" berhasil ditambahkan.");
    }

    public function updateInterest(Request $request, Interest $interest)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'desc' => 'nullable|string|max:255',
            'icon' => 'nullable|string',
        ]);

        $interest->update($request->only(['name', 'desc', 'icon']));

        return redirect()->route('admin.dashboard', ['tab' => 'interests'])->with('success', "Interest \"{$interest->name}\" berhasil diperbarui.");
    }

    public function deleteInterest(Interest $interest)
    {
        $name = $interest->name;
        $interest->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'interests'])->with('success', "Interest \"{$name}\" berhasil dihapus.");
    }

    // ─── FOKUS CRUD ───

    public function storeFokus(Request $request)
    {
        $request->validate([
            'interest_val' => 'required|string|exists:interests,val',
            'val'          => 'required|string|max:50|unique:fokus,val',
            'name'         => 'required|string|max:100',
            'desc'         => 'nullable|string|max:255',
            'icon'         => 'nullable|string',
            'tags'         => 'nullable|string|max:255',
        ]);

        Fokus::create($request->only(['interest_val', 'val', 'name', 'desc', 'icon', 'tags']));

        return redirect()->route('admin.dashboard', ['tab' => 'fokus'])->with('success', "Fokus \"{$request->name}\" berhasil ditambahkan.");
    }

    public function updateFokus(Request $request, Fokus $fokus)
    {
        $request->validate([
            'interest_val' => 'required|string|exists:interests,val',
            'name'         => 'required|string|max:100',
            'desc'         => 'nullable|string|max:255',
            'icon'         => 'nullable|string',
            'tags'         => 'nullable|string|max:255',
        ]);

        $fokus->update($request->only(['interest_val', 'name', 'desc', 'icon', 'tags']));

        return redirect()->route('admin.dashboard', ['tab' => 'fokus'])->with('success', "Fokus \"{$fokus->name}\" berhasil diperbarui.");
    }

    public function deleteFokus(Fokus $fokus)
    {
        $name = $fokus->name;
        $fokus->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'fokus'])->with('success', "Fokus \"{$name}\" berhasil dihapus.");
    }

    // ─── SUBMATERI CRUD ───

    public function storeSubmateri(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'order'       => 'required|integer|min:0'
        ]);

        Submateri::create($request->only(['course_id', 'title', 'description', 'icon', 'order']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri'])->with('success', "Sub Materi \"{$request->title}\" berhasil ditambahkan.");
    }

    public function updateSubmateri(Request $request, Submateri $submateri)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'order'       => 'required|integer|min:0'
        ]);

        $submateri->update($request->only(['course_id', 'title', 'description', 'icon', 'order']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri'])->with('success', "Sub Materi \"{$submateri->title}\" berhasil diperbarui.");
    }

    public function uploadMedia(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,ogg,avi,mov|max:51200', // 50MB max
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                $destinationPath = public_path('uploads/media');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $url = asset('uploads/media/' . $fileName);

                return response()->json([
                    'success' => true,
                    'url' => $url
                ]);
            }
            return response()->json(['success' => false, 'error' => 'File tidak ditemukan'], 400);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteSubmateri(Submateri $submateri)
    {
        $title = $submateri->title;
        $submateri->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'submateri'])->with('success', "Sub Materi \"{$title}\" berhasil dihapus.");
    }

    // ─── QUIZ CRUD ───

    public function storeQuiz(Request $request)
    {
        $request->validate([
            'lesson_id'      => 'required|exists:lessons,id',
            'question'       => 'required|string',
            'correct_answer' => 'required|string',
            'explanation'    => 'nullable|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
        ]);

        $options = [
            $request->option_a,
            $request->option_b,
            $request->option_c,
            $request->option_d
        ];

        Quiz::create([
            'lesson_id'      => $request->lesson_id,
            'question'       => $request->question,
            'options'        => $options,
            'correct_answer' => $request->correct_answer,
            'explanation'    => $request->explanation,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'quizzes'])->with('success', "Soal kuis baru berhasil ditambahkan.");
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'lesson_id'      => 'required|exists:lessons,id',
            'question'       => 'required|string',
            'correct_answer' => 'required|string',
            'explanation'    => 'nullable|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
        ]);

        $options = [
            $request->option_a,
            $request->option_b,
            $request->option_c,
            $request->option_d
        ];

        $quiz->update([
            'lesson_id'      => $request->lesson_id,
            'question'       => $request->question,
            'options'        => $options,
            'correct_answer' => $request->correct_answer,
            'explanation'    => $request->explanation,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'quizzes'])->with('success', "Soal kuis berhasil diperbarui.");
    }

    public function deleteQuiz(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'quizzes'])->with('success', "Soal kuis berhasil dihapus.");
    }

    public function getTableData($tableName)
    {
        // Security check: make sure the table exists in rawTables to prevent SQL injection
        $rawTables = \Illuminate\Support\Facades\Schema::getTables();
        $tableExists = false;
        foreach ($rawTables as $t) {
            if ($t['name'] === $tableName) {
                $tableExists = true;
                break;
            }
        }

        if (!$tableExists) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        try {
            $data = \Illuminate\Support\Facades\DB::table($tableName)->limit(15)->get();
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing($tableName);
            return response()->json([
                'table' => $tableName,
                'columns' => $columns,
                'rows' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
