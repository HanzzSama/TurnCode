<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Notification;
use App\Services\MissionService;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function showCourse(Course $course, Request $request)
    {
        $course->load(['submateris.chapters.lessons']);
        
        // Cek progress user
        $completedLessons = auth()->user()->lessons()->pluck('lesson_id')->toArray();

        // Ambil submateri_id dari request, default ke submateri pertama
        $activeSubmateriId = $request->query('submateri_id');
        if (!$activeSubmateriId && $course->submateris->isNotEmpty()) {
            $activeSubmateriId = $course->submateris->first()->id;
        }

        // Cek apakah submateri aktif sudah 100% selesai
        $activeSubmateri = $course->submateris->firstWhere('id', $activeSubmateriId);
        $isSubmateriCompleted = false;
        
        if ($activeSubmateri) {
            $submateriLessons = collect();
            foreach ($activeSubmateri->chapters as $chapter) {
                foreach ($chapter->lessons as $lsn) {
                    $submateriLessons->push($lsn->id);
                }
            }
            
            if ($submateriLessons->count() > 0) {
                $completedCount = 0;
                foreach ($submateriLessons as $lsnId) {
                    if (in_array($lsnId, $completedLessons)) {
                        $completedCount++;
                    }
                }
                $isSubmateriCompleted = $completedCount === $submateriLessons->count();
            }
        }

        // Ambil jadwal user
        $schedules = \App\Models\Schedule::where('user_id', auth()->id())->get();

        return view('learning.course', compact('course', 'completedLessons', 'activeSubmateriId', 'schedules', 'isSubmateriCompleted'));
    }

    public function showLesson(Lesson $lesson)
    {
        $lesson->load(['chapter.submateri.course', 'quizzes']);
        
        $course = $lesson->chapter->submateri->course;
        
        // Ambil list semua submateri, bab, dan pelajaran dalam kursus ini untuk navigasi sidebar
        $submateris = $course->submateris()->with(['chapters' => function($q) {
            $q->orderBy('order');
        }, 'chapters.lessons' => function($q) {
            $q->orderBy('order');
        }])->get();
        
        $completedLessons = auth()->user()->lessons()->pluck('lesson_id')->toArray();

        // Cari next lesson di seluruh course
        $allLessons = collect();
        foreach ($submateris as $submateri) {
            foreach ($submateri->chapters as $chapter) {
                foreach ($chapter->lessons as $lsn) {
                    $allLessons->push($lsn);
                }
            }
        }
        $currentIndex = $allLessons->search(function ($item) use ($lesson) {
            return $item->id == $lesson->id;
        });
        $nextLesson = null;
        if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
            $nextLesson = $allLessons[$currentIndex + 1];
        }

        return view('learning.lesson', compact('lesson', 'course', 'submateris', 'completedLessons', 'nextLesson'));
    }

    public function submitQuiz(Request $request, Lesson $lesson)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answer' => 'required|string'
        ]);

        $quiz = Quiz::findOrFail($request->quiz_id);
        
        $isCorrect = $quiz->correct_answer === $request->answer;

        if ($isCorrect) {
            auth()->user()->lessons()->syncWithoutDetaching([$lesson->id]);

            // Update daily mission progress
            MissionService::updateProgress(auth()->user(), 'finish_quiz');

            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Materi Selesai 🎉',
                'description' => "Selamat! Kamu telah menyelesaikan materi '{$lesson->title}' di kelas '{$lesson->chapter->submateri->course->title}'.",
                'type' => 'learning',
            ]);

            return response()->json(['correct' => true, 'explanation' => $quiz->explanation]);
        }

        return response()->json(['correct' => false, 'explanation' => $quiz->explanation]);
    }

    public function completeLesson(Lesson $lesson)
    {
        auth()->user()->lessons()->syncWithoutDetaching([$lesson->id]);

        // Update daily mission progress
        MissionService::updateProgress(auth()->user(), 'read_lesson');

        Notification::create([
            'user_id' => auth()->id(),
            'title' => 'Materi Selesai 🎉',
            'description' => "Selamat! Kamu telah menyelesaikan materi '{$lesson->title}' di kelas '{$lesson->chapter->submateri->course->title}'.",
            'type' => 'learning',
        ]);

        $course = $lesson->chapter->submateri->course;
        $submateris = $course->submateris()->with(['chapters' => function($q) {
            $q->orderBy('order');
        }, 'chapters.lessons' => function($q) {
            $q->orderBy('order');
        }])->get();
        
        $allLessons = collect();
        foreach ($submateris as $submateri) {
            foreach ($submateri->chapters as $chapter) {
                foreach ($chapter->lessons as $lsn) {
                    $allLessons->push($lsn);
                }
            }
        }
        $currentIndex = $allLessons->search(function ($item) use ($lesson) {
            return $item->id == $lesson->id;
        });
        
        if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
            $nextLesson = $allLessons[$currentIndex + 1];
            return redirect()->route('lessons.show', $nextLesson->id);
        }

        return redirect()->route('courses.show', [$lesson->chapter->submateri->course->id, 'submateri_id' => $lesson->chapter->submateri->id]);
    }
}
