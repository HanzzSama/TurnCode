<?php

namespace App\Http\Controllers;

use App\Models\Submateri;
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

        $isSubmateriCompleted = $submateriLessons->count() > 0 && $completedCount === $submateriLessons->count();

        if (!$isSubmateriCompleted) {
            return redirect()->back()->with('error', 'Kamu belum menyelesaikan semua materi di bagian ini.');
        }

        // Get the date of the last completed lesson in this submateri
        $lastCompletedLesson = $user->lessons()
            ->whereIn('lesson_id', $submateriLessons)
            ->orderBy('lesson_user.created_at', 'desc')
            ->first();

        $completionDate = $lastCompletedLesson ? Carbon::parse($lastCompletedLesson->pivot->created_at) : now();

        return view('learning.certificate', compact('user', 'submateri', 'courseTitle', 'completionDate'));
    }
}
