<?php

namespace App\Http\Controllers;

use App\Models\Submateri;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CertificateController extends Controller
{
    public function generate(Submateri $submateri)
    {
        $user = auth()->user();
        
        // Load relationships
        $submateri->load('chapters.lessons');
        $courseTitle = $submateri->course->title ?? 'Course';

        // Check completion
        $completedLessons = $user->lessons()->pluck('lesson_id')->toArray();
        $submateriLessons = collect();
        foreach ($submateri->chapters as $chapter) {
            foreach ($chapter->lessons as $lsn) {
                $submateriLessons->push($lsn->id);
            }
        }

        $completedCount = 0;
        foreach ($submateriLessons as $lsnId) {
            if (in_array($lsnId, $completedLessons)) {
                $completedCount++;
            }
        }

        $passedSubmateriQuizzes = $user->achievements['passed_submateri_quizzes'] ?? [];
        $isQuizPassed = in_array($submateri->id, $passedSubmateriQuizzes);
        $isSubmateriCompleted = $submateriLessons->count() > 0 && $completedCount === $submateriLessons->count() && $isQuizPassed;

        if (!$isSubmateriCompleted) {
            return redirect()->back()->with('error', 'Kamu belum menyelesaikan kuis atau materi di bagian ini.');
        }

        // Get the date of the last completed lesson in this submateri
        $lastCompletedLesson = $user->lessons()
            ->whereIn('lesson_id', $submateriLessons)
            ->orderBy('lesson_user.created_at', 'desc')
            ->first();

        $completionDate = $lastCompletedLesson ? Carbon::parse($lastCompletedLesson->pivot->created_at) : now();

        return view('learning.certificate', compact('user', 'submateri', 'courseTitle', 'completionDate'));
    }

    public function generateFocus(Course $course)
    {
        $user = auth()->user();
        
        // Check if user has passed the exam
        $achievements = $user->achievements ?? [];
        $passedExams = $achievements['passed_exams'] ?? [];
        
        if (!in_array($course->id, $passedExams)) {
            return redirect()->route('dashboard')->with('error', 'Kamu belum lulus ujian akhir untuk fokus ini.');
        }

        $completionDate = now();

        return view('learning.focus_certificate', compact('user', 'course', 'completionDate'));
    }
}
