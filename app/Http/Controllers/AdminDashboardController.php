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
        $submateris = Submateri::with(['course', 'chapters.lessons'])->orderBy('course_id')->orderBy('order')->get();
        $courses = Course::orderBy('title')->get();
        $chapters = Chapter::with('submateri.course')->orderBy('submateri_id')->orderBy('order')->get();
        $lessons = Lesson::with('chapter.submateri.course')->orderBy('chapter_id')->orderBy('order')->get();
        $quizzes = Quiz::with('lesson.chapter.submateri.course')->orderBy('id', 'desc')->get();
        $activeSubtab = $request->query('subtab', 'submateri-main');

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
            'chapters',
            'lessons',
            'quizzes',
            'paginatedUsers',
            'activeTab',
            'activeSubtab',
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
            'order'       => 'required|integer|min:0',
            'status'      => 'required|in:published,draft,coming_soon'
        ]);

        Submateri::create($request->only(['course_id', 'title', 'description', 'icon', 'order', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri'])->with('success', "Sub Materi \"{$request->title}\" berhasil ditambahkan.");
    }

    public function updateSubmateri(Request $request, Submateri $submateri)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'order'       => 'required|integer|min:0',
            'status'      => 'required|in:published,draft,coming_soon'
        ]);

        $submateri->update($request->only(['course_id', 'title', 'description', 'icon', 'order', 'status']));

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

    // ─── CHAPTER CRUD ───

    public function storeChapter(Request $request)
    {
        $request->validate([
            'submateri_id' => 'required|exists:submateris,id',
            'title'        => 'required|string|max:100',
            'order'        => 'required|integer|min:0',
            'status'       => 'required|in:published,draft,coming_soon'
        ]);

        Chapter::create($request->only(['submateri_id', 'title', 'order', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'bab'])->with('success', "Bab \"{$request->title}\" berhasil ditambahkan.");
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'submateri_id' => 'required|exists:submateris,id',
            'title'        => 'required|string|max:100',
            'order'        => 'required|integer|min:0',
            'status'       => 'required|in:published,draft,coming_soon'
        ]);

        $chapter->update($request->only(['submateri_id', 'title', 'order', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'bab'])->with('success', "Bab \"{$chapter->title}\" berhasil diperbarui.");
    }

    public function deleteChapter(Chapter $chapter)
    {
        $title = $chapter->title;
        $chapter->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'bab'])->with('success', "Bab \"{$title}\" berhasil dihapus.");
    }

    // ─── LESSON CRUD ───

    public function storeLesson(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'title'      => 'required|string|max:100',
            'content'    => 'nullable|string',
            'order'      => 'required|integer|min:0',
            'status'     => 'required|in:published,draft,coming_soon'
        ]);

        Lesson::create($request->only(['chapter_id', 'title', 'content', 'order', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'halaman'])->with('success', "Halaman \"{$request->title}\" berhasil ditambahkan.");
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'title'      => 'required|string|max:100',
            'content'    => 'nullable|string',
            'order'      => 'required|integer|min:0',
            'status'     => 'required|in:published,draft,coming_soon'
        ]);

        $lesson->update($request->only(['chapter_id', 'title', 'content', 'order', 'status']));

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'halaman'])->with('success', "Halaman \"{$lesson->title}\" berhasil diperbarui.");
    }

    public function deleteLesson(Lesson $lesson)
    {
        $title = $lesson->title;
        $lesson->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'submateri', 'subtab' => 'halaman'])->with('success', "Halaman \"{$title}\" berhasil dihapus.");
    }

    // ─── QUIZ CRUD ───

    public function storeQuiz(Request $request)
    {
        if ($request->has('questions')) {
            $request->validate([
                'questions' => 'required|array|min:1',
                'questions.*.lesson_id' => 'required|exists:lessons,id',
                'questions.*.type' => 'required|in:text,code,puzzle,code_writing',
                'questions.*.question' => 'required|string',
                'questions.*.image_url' => 'nullable|string',
                'questions.*.video_url' => 'nullable|string',
                'questions.*.code_block' => 'nullable|string',
                'questions.*.explanation' => 'nullable|string',
            ]);

            $count = 0;
            foreach ($request->questions as $qData) {
                $type = $qData['type'] ?? 'text';
                $options = [];
                $correctAnswer = '';

                if ($type === 'puzzle') {
                    $linesRaw = $qData['puzzle_lines'] ?? '';
                    $lines = array_filter(array_map('trim', explode("\n", $linesRaw)));
                    if (count($lines) < 2) {
                        return redirect()->back()->withErrors(['questions' => 'Puzzle harus memiliki minimal 2 baris kode.'])->withInput();
                    }
                    $correctAnswer = json_encode(array_values($lines));
                    $scrambled = $lines;
                    shuffle($scrambled);
                    if ($scrambled === $lines && count($lines) > 1) {
                        shuffle($scrambled);
                    }
                    $options = array_values($scrambled);
                } elseif ($type === 'code_writing') {
                    $correctAnswer = trim($qData['correct_answer_code'] ?? '');
                    $options = [];
                } else {
                    $options = [
                        trim($qData['option_a'] ?? ''),
                        trim($qData['option_b'] ?? ''),
                        trim($qData['option_c'] ?? ''),
                        trim($qData['option_d'] ?? '')
                    ];
                    $correctKey = $qData['correct_answer'] ?? 'a';
                    $correctAnswer = match($correctKey) {
                        'b' => $options[1],
                        'c' => $options[2],
                        'd' => $options[3],
                        default => $options[0]
                    };
                }

                Quiz::create([
                    'lesson_id' => $qData['lesson_id'],
                    'type' => $type,
                    'question' => $qData['question'],
                    'image_url' => $qData['image_url'] ?? null,
                    'video_url' => $qData['video_url'] ?? null,
                    'code_block' => $qData['code_block'] ?? null,
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'explanation' => $qData['explanation'] ?? null,
                ]);
                $count++;
            }

            return redirect()->route('admin.dashboard', ['tab' => 'quizzes'])->with('success', "{$count} soal kuis baru berhasil ditambahkan.");
        }

        // Single question fallback
        $request->validate([
            'lesson_id'      => 'required|exists:lessons,id',
            'type'           => 'required|in:text,code,puzzle',
            'question'       => 'required|string',
            'image_url'      => 'nullable|string',
            'video_url'      => 'nullable|string',
            'code_block'     => 'nullable|string',
            'explanation'    => 'nullable|string',
        ]);

        $type = $request->input('type', 'text');
        $options = [];
        $correctAnswer = '';

        if ($type === 'puzzle') {
            $linesRaw = $request->input('puzzle_lines', '');
            $lines = array_filter(array_map('trim', explode("\n", $linesRaw)));
            if (count($lines) < 2) {
                return redirect()->back()->withErrors(['puzzle_lines' => 'Puzzle harus memiliki minimal 2 baris kode.'])->withInput();
            }
            $correctAnswer = json_encode(array_values($lines));
            $scrambled = $lines;
            shuffle($scrambled);
            if ($scrambled === $lines && count($lines) > 1) {
                shuffle($scrambled);
            }
            $options = array_values($scrambled);
        } elseif ($type === 'code_writing') {
            $correctAnswer = trim($request->input('correct_answer_code', ''));
            $options = [];
        } else {
            $request->validate([
                'option_a'       => 'required|string',
                'option_b'       => 'required|string',
                'option_c'       => 'required|string',
                'option_d'       => 'required|string',
                'correct_answer' => 'required|string',
            ]);

            $options = [
                trim($request->option_a),
                trim($request->option_b),
                trim($request->option_c),
                trim($request->option_d)
            ];

            $correctKey = $request->correct_answer;
            $correctAnswer = match($correctKey) {
                'b' => $options[1],
                'c' => $options[2],
                'd' => $options[3],
                default => $options[0]
            };
        }

        Quiz::create([
            'lesson_id'      => $request->lesson_id,
            'type'           => $type,
            'question'       => $request->question,
            'image_url'      => $request->image_url,
            'video_url'      => $request->video_url,
            'code_block'     => $request->code_block,
            'options'        => $options,
            'correct_answer' => $correctAnswer,
            'explanation'    => $request->explanation,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'quizzes'])->with('success', "Soal kuis baru berhasil ditambahkan.");
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'lesson_id'      => 'required|exists:lessons,id',
            'type'           => 'required|in:text,code,puzzle,code_writing',
            'question'       => 'required|string',
            'image_url'      => 'nullable|string',
            'video_url'      => 'nullable|string',
            'code_block'     => 'nullable|string',
            'explanation'    => 'nullable|string',
        ]);

        $type = $request->input('type', 'text');
        $options = [];
        $correctAnswer = '';

        if ($type === 'puzzle') {
            $linesRaw = $request->input('puzzle_lines', '');
            $lines = array_filter(array_map('trim', explode("\n", $linesRaw)));
            if (count($lines) < 2) {
                return redirect()->back()->withErrors(['puzzle_lines' => 'Puzzle harus memiliki minimal 2 baris kode.'])->withInput();
            }
            $correctAnswer = json_encode(array_values($lines));
            $scrambled = $lines;
            shuffle($scrambled);
            if ($scrambled === $lines && count($lines) > 1) {
                shuffle($scrambled);
            }
            $options = array_values($scrambled);
        } elseif ($type === 'code_writing') {
            $correctAnswer = trim($request->input('correct_answer_code', ''));
            $options = [];
        } else {
            $request->validate([
                'option_a'       => 'required|string',
                'option_b'       => 'required|string',
                'option_c'       => 'required|string',
                'option_d'       => 'required|string',
                'correct_answer' => 'required|string',
            ]);

            $options = [
                trim($request->option_a),
                trim($request->option_b),
                trim($request->option_c),
                trim($request->option_d)
            ];

            $correctKey = $request->correct_answer;
            if (in_array($correctKey, ['a', 'b', 'c', 'd'])) {
                $correctAnswer = match($correctKey) {
                    'b' => $options[1],
                    'c' => $options[2],
                    'd' => $options[3],
                    default => $options[0]
                };
            } else {
                $correctAnswer = trim($correctKey);
            }
        }

        $quiz->update([
            'lesson_id'      => $request->lesson_id,
            'type'           => $type,
            'question'       => $request->question,
            'image_url'      => $request->image_url,
            'video_url'      => $request->video_url,
            'code_block'     => $request->code_block,
            'options'        => $options,
            'correct_answer' => $correctAnswer,
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
