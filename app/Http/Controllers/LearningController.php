<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Submateri;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Notification;
use App\Services\MissionService;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function showCourse(Course $course, Request $request)
    {
        $course->load([
            'submateris' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            },
            'submateris.chapters' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            },
            'submateris.chapters.lessons' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            }
        ]);

        // Cek progress user
        $completedLessons = auth()->user()->lessons()->pluck('lesson_id')->toArray();

        // Ambil submateri_id dari request, default ke submateri pertama yang dipublish
        $activeSubmateriId = $request->query('submateri_id');
        if (!$activeSubmateriId && $course->submateris->isNotEmpty()) {
            $publishedSub = $course->submateris->firstWhere('status', 'published');
            $activeSubmateriId = $publishedSub ? $publishedSub->id : $course->submateris->first()->id;
        }

        // Cek apakah submateri aktif sudah 100% selesai
        $activeSubmateri = $course->submateris->firstWhere('id', $activeSubmateriId);
        $isSubmateriCompleted = false;

        if ($activeSubmateri) {
            if ($activeSubmateri->status === 'draft') {
                abort(404);
            }
            $submateriLessons = collect();
            if ($activeSubmateri->status !== 'coming_soon') {
                foreach ($activeSubmateri->chapters as $chapter) {
                    if ($chapter->status === 'coming_soon')
                        continue;
                    foreach ($chapter->lessons as $lsn) {
                        if ($lsn->status !== 'coming_soon') {
                            $submateriLessons->push($lsn->id);
                        }
                    }
                }
            }

            if ($submateriLessons->count() > 0) {
                $completedCount = 0;
                foreach ($submateriLessons as $lsnId) {
                    if (in_array($lsnId, $completedLessons)) {
                        $completedCount++;
                    }
                }
                $isQuizPassed = in_array($activeSubmateri->id, auth()->user()->achievements['passed_submateri_quizzes'] ?? []);
                $isSubmateriCompleted = ($completedCount === $submateriLessons->count()) && $isQuizPassed;
            }
        }

        // Ambil jadwal user
        $schedules = \App\Models\Schedule::where('user_id', auth()->id())->get();

        return view('learning.course', compact('course', 'completedLessons', 'activeSubmateriId', 'schedules', 'isSubmateriCompleted'));
    }

    public function showLesson(Lesson $lesson)
    {
        $lesson->load([
            'chapter.submateri.course',
            'chapter.lessons' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            },
            'chapter.lessons.quizzes',
            'quizzes'
        ]);

        if (
            $lesson->status === 'draft' ||
            $lesson->chapter->status === 'draft' ||
            $lesson->chapter->submateri->status === 'draft'
        ) {
            abort(404);
        }

        if (
            $lesson->status === 'coming_soon' ||
            $lesson->chapter->status === 'coming_soon' ||
            $lesson->chapter->submateri->status === 'coming_soon'
        ) {
            return redirect()->route('courses.show', $lesson->chapter->submateri->course_id)
                ->with('error', 'Materi ini belum tersedia (Coming Soon).');
        }

        $course = $lesson->chapter->submateri->course;

        // Ambil list semua submateri, bab, dan pelajaran dalam kursus ini untuk navigasi sidebar
        $submateris = $course->submateris()
            ->where('status', '!=', 'draft')
            ->with([
                'chapters' => function ($q) {
                    $q->where('status', '!=', 'draft')->orderBy('order');
                },
                'chapters.lessons' => function ($q) {
                    $q->where('status', '!=', 'draft')->orderBy('order');
                }
            ])
            ->orderBy('order')
            ->get();

        $completedLessons = auth()->user()->lessons()->pluck('lesson_id')->toArray();

        // Cari next lesson di dalam submateri aktif
        $currentSubmateri = $lesson->chapter->submateri;
        $submateriLessons = collect();
        foreach ($currentSubmateri->chapters()->where('status', '!=', 'draft')->orderBy('order')->get() as $chapter) {
            if ($chapter->status === 'coming_soon')
                continue;
            foreach ($chapter->lessons()->where('status', '!=', 'draft')->orderBy('order')->get() as $lsn) {
                if ($lsn->status !== 'coming_soon') {
                    $submateriLessons->push($lsn);
                }
            }
        }
        $currentIndex = $submateriLessons->search(function ($item) use ($lesson) {
            return $item->id == $lesson->id;
        });
        $nextLesson = null;
        if ($currentIndex !== false && $currentIndex < $submateriLessons->count() - 1) {
            $nextLesson = $submateriLessons[$currentIndex + 1];
        }

        $discussions = $lesson->discussions()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return view('learning.lesson', compact('lesson', 'course', 'submateris', 'completedLessons', 'nextLesson', 'discussions'));
    }

    public function showSubmateriQuiz(Submateri $submateri)
    {
        $submateri->load([
            'course',
            'chapters' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            },
            'chapters.lessons' => function ($q) {
                $q->where('status', '!=', 'draft')->orderBy('order');
            },
            'chapters.lessons.quizzes'
        ]);

        if ($submateri->status === 'draft' || $submateri->course->status === 'draft') {
            abort(404);
        }

        if ($submateri->status === 'coming_soon') {
            return redirect()->route('courses.show', $submateri->course_id)
                ->with('error', 'Materi ini belum tersedia (Coming Soon).');
        }

        $course = $submateri->course;

        // Verify that all lessons in this submateri are completed
        $completedLessons = auth()->user()->lessons()->pluck('lesson_id')->toArray();
        $submateriLessons = collect();
        foreach ($submateri->chapters as $chapter) {
            if ($chapter->status === 'coming_soon')
                continue;
            foreach ($chapter->lessons as $lsn) {
                if ($lsn->status !== 'coming_soon') {
                    $submateriLessons->push($lsn->id);
                }
            }
        }

        $completedCount = 0;
        foreach ($submateriLessons as $lsnId) {
            if (in_array($lsnId, $completedLessons)) {
                $completedCount++;
            }
        }

        if ($submateriLessons->count() === 0 || $completedCount < $submateriLessons->count()) {
            return redirect()->route('courses.show', [$course->id, 'submateri_id' => $submateri->id])
                ->with('error', 'Selesaikan seluruh pelajaran terlebih dahulu sebelum memulai Uji Pemahaman.');
        }

        // Get all quizzes under this submateri
        $quizzes = Quiz::whereHas('lesson.chapter', function ($q) use ($submateri) {
            $q->where('submateri_id', $submateri->id)
                ->where('status', '!=', 'draft');
        })->whereHas('lesson', function ($q) {
            $q->where('status', '!=', 'draft');
        })->get();

        // Get sidebar list
        $submateris = $course->submateris()
            ->where('status', '!=', 'draft')
            ->with([
                'chapters' => function ($q) {
                    $q->where('status', '!=', 'draft')->orderBy('order');
                },
                'chapters.lessons' => function ($q) {
                    $q->where('status', '!=', 'draft')->orderBy('order');
                }
            ])
            ->orderBy('order')
            ->get();

        $isQuizPassed = in_array($submateri->id, auth()->user()->achievements['passed_submateri_quizzes'] ?? []);

        return view('learning.submateri_quiz', compact('submateri', 'course', 'submateris', 'completedLessons', 'quizzes', 'isQuizPassed'));
    }

    public function submitSubmateriQuiz(Request $request, Submateri $submateri)
    {
        $request->validate([
            'answers' => 'required|array'
        ]);

        if ($submateri->status === 'draft') {
            abort(404);
        }

        $quizzes = Quiz::whereHas('lesson.chapter', function ($q) use ($submateri) {
            $q->where('submateri_id', $submateri->id)
                ->where('status', '!=', 'draft');
        })->whereHas('lesson', function ($q) {
            $q->where('status', '!=', 'draft');
        })->get();

        $correctCount = 0;
        $explanations = [];

        foreach ($quizzes as $quiz) {
            $userAns = $request->answers[$quiz->id] ?? null;
            $isCorrect = false;

            if (($quiz->type ?? 'text') === 'puzzle') {
                $correctArray = json_decode($quiz->correct_answer, true) ?: [];
                if (is_array($userAns) && is_array($correctArray) && count($userAns) === count($correctArray)) {
                    $isCorrect = true;
                    foreach ($correctArray as $idx => $correctLine) {
                        $uLine = isset($userAns[$idx]) ? str_replace("\r\n", "\n", trim((string)$userAns[$idx])) : '';
                        $cLine = str_replace("\r\n", "\n", trim((string)$correctLine));
                        if ($uLine !== $cLine) {
                            $isCorrect = false;
                            break;
                        }
                    }
                } else {
                    $userAnsNormalized = str_replace("\r\n", "\n", trim((string)($userAns ?? '')));
                    $correctAnsNormalized = str_replace("\r\n", "\n", trim((string)($quiz->correct_answer ?? '')));
                    $isCorrect = $userAnsNormalized === $correctAnsNormalized;
                }
            } elseif (($quiz->type ?? 'text') === 'code_writing') {
                // Ignore all whitespace for flexible code writing comparison
                $uLine = preg_replace('/\s+/', '', (string)($userAns ?? ''));
                $cLine = preg_replace('/\s+/', '', (string)($quiz->correct_answer ?? ''));
                $isCorrect = ($uLine === $cLine && $cLine !== '');
            } else {
                $userAnsNormalized = str_replace("\r\n", "\n", trim((string)($userAns ?? '')));
                $correctAnsNormalized = str_replace("\r\n", "\n", trim((string)($quiz->correct_answer ?? '')));
                $isCorrect = $userAnsNormalized === $correctAnsNormalized;
            }

            if ($isCorrect) {
                $correctCount++;
            }
            $explanations[$quiz->id] = [
                'correct' => $isCorrect,
                'explanation' => $quiz->explanation,
                'correct_answer' => $quiz->correct_answer
            ];
        }

        $totalQuestions = $quizzes->count();
        $passed = $correctCount === $totalQuestions;

        if ($passed) {
            $user = auth()->user();
            $achievements = $user->achievements ?? [];
            $passedQuizzes = $achievements['passed_submateri_quizzes'] ?? [];
            if (!in_array($submateri->id, $passedQuizzes)) {
                $passedQuizzes[] = (int) $submateri->id;
                $achievements['passed_submateri_quizzes'] = $passedQuizzes;
                $user->achievements = $achievements;
                $user->exp += 50; // award 50 EXP

                // Update daily mission progress for completing a quiz
                MissionService::updateProgress($user, 'finish_quiz');

                $user->save();

                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Lulus Uji Pemahaman! 💡',
                    'description' => "Selamat! Kamu telah menyelesaikan Uji Pemahaman untuk submateri '{$submateri->title}' di kelas '{$submateri->course->title}'.",
                    'type' => 'learning',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'passed' => $passed,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'explanations' => $explanations
        ]);
    }

    public function completeLesson(Lesson $lesson)
    {
        if (
            $lesson->status === 'draft' ||
            $lesson->chapter->status === 'draft' ||
            $lesson->chapter->submateri->status === 'draft'
        ) {
            abort(404);
        }

        if (
            $lesson->status === 'coming_soon' ||
            $lesson->chapter->status === 'coming_soon' ||
            $lesson->chapter->submateri->status === 'coming_soon'
        ) {
            return redirect()->route('courses.show', $lesson->chapter->submateri->course_id);
        }

        auth()->user()->lessons()->syncWithoutDetaching([$lesson->id]);

        // Update daily mission progress
        MissionService::updateProgress(auth()->user(), 'read_lesson');

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Materi Selesai 🎉',
            'description' => "Selamat! Kamu telah menyelesaikan materi '{$lesson->title}' di kelas '{$lesson->chapter->submateri->course->title}'.",
            'type' => 'learning',
        ]);

        $currentSubmateri = $lesson->chapter->submateri;
        $submateriLessons = collect();
        foreach ($currentSubmateri->chapters()->where('status', '!=', 'draft')->orderBy('order')->get() as $chapter) {
            if ($chapter->status === 'coming_soon')
                continue;
            foreach ($chapter->lessons()->where('status', '!=', 'draft')->orderBy('order')->get() as $lsn) {
                if ($lsn->status !== 'coming_soon') {
                    $submateriLessons->push($lsn);
                }
            }
        }
        $currentIndex = $submateriLessons->search(function ($item) use ($lesson) {
            return $item->id == $lesson->id;
        });

        if ($currentIndex !== false && $currentIndex < $submateriLessons->count() - 1) {
            $nextLesson = $submateriLessons[$currentIndex + 1];
            return redirect()->route('lessons.show', $nextLesson->id);
        }

        // Redirect to Submateri Quiz room if it is the last lesson in the submateri
        return redirect()->route('submateris.quiz.show', $currentSubmateri->id);
    }
}
