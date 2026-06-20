<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Notification;

class ExamController extends Controller
{
    private function checkEligibility($user, Course $course)
    {
        $submateris = $course->submateris()
            ->where('status', '!=', 'draft')
            ->with(['chapters' => function($q) {
                $q->where('status', '!=', 'draft');
            }, 'chapters.lessons' => function($q) {
                $q->where('status', '!=', 'draft');
            }])
            ->get();

        $completedLessons = $user->lessons()->pluck('lesson_id')->toArray();
        $totalCourseLessons = 0;
        $completedCourseLessons = 0;

        foreach ($submateris as $sub) {
            if ($sub->status === 'coming_soon') continue;
            foreach ($sub->chapters as $chap) {
                if ($chap->status === 'coming_soon') continue;
                foreach ($chap->lessons as $lsn) {
                    if ($lsn->status !== 'coming_soon') {
                        $totalCourseLessons++;
                        if (in_array($lsn->id, $completedLessons)) {
                            $completedCourseLessons++;
                        }
                    }
                }
            }
        }

        $passedSubmateriQuizzes = $user->achievements['passed_submateri_quizzes'] ?? [];
        $allQuizzesPassed = true;
        foreach ($submateris as $sub) {
            if ($sub->status === 'coming_soon') continue;
            if (!in_array($sub->id, $passedSubmateriQuizzes)) {
                $allQuizzesPassed = false;
                break;
            }
        }

        return $totalCourseLessons > 0 && $completedCourseLessons === $totalCourseLessons && $allQuizzesPassed;
    }

    public function agreement()
    {
        $user = auth()->user();
        $userCourse = $user->getActiveCourse();

        if (!$this->checkEligibility($user, $userCourse)) {
            return redirect()->route('dashboard')->with('error', 'Kamu belum menyelesaikan seluruh materi pelajaran di fokus ini.');
        }

        return view('exam.agreement', compact('userCourse'));
    }

    public function room()
    {
        $user = auth()->user();
        $userCourse = $user->getActiveCourse();

        if (!$this->checkEligibility($user, $userCourse)) {
            return redirect()->route('dashboard')->with('error', 'Kamu belum menyelesaikan seluruh materi pelajaran di fokus ini.');
        }

        $quizzes = Quiz::whereHas('lesson.chapter.submateri', function($q) use ($userCourse) {
            $q->where('course_id', $userCourse->id)
              ->where('status', '!=', 'draft');
        })->whereHas('lesson.chapter', function($q) {
            $q->where('status', '!=', 'draft');
        })->whereHas('lesson', function($q) {
            $q->where('status', '!=', 'draft');
        })->get();

        return view('exam.room', compact('userCourse', 'quizzes'));
    }

    public function submit(Request $request)
    {
        $user = auth()->user();
        $userCourse = $user->getActiveCourse();

        if (!$this->checkEligibility($user, $userCourse)) {
            return response()->json(['success' => false, 'message' => 'Kamu belum berhak mengikuti ujian ini.'], 403);
        }

        $answers = $request->input('answers', []); // quiz_id => selected_answer

        $quizzes = Quiz::whereHas('lesson.chapter.submateri', function($q) use ($userCourse) {
            $q->where('course_id', $userCourse->id)
              ->where('status', '!=', 'draft');
        })->whereHas('lesson.chapter', function($q) {
            $q->where('status', '!=', 'draft');
        })->whereHas('lesson', function($q) {
            $q->where('status', '!=', 'draft');
        })->get();

        if ($quizzes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada soal kuis ditemukan.'], 400);
        }

        $correctCount = 0;
        foreach ($quizzes as $quiz) {
            $userAns = $answers[$quiz->id] ?? null;
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
        }

        $totalQuestions = $quizzes->count();
        $score = round(($correctCount / $totalQuestions) * 100);
        $passed = $score >= 70; // 70% passing grade

        if ($passed) {
            $achievements = $user->achievements ?? [];
            $passedExams = $achievements['passed_exams'] ?? [];
            if (!in_array($userCourse->id, $passedExams)) {
                $passedExams[] = (int)$userCourse->id;
                $achievements['passed_exams'] = $passedExams;
                $user->achievements = $achievements;
                $user->exp += 150; // award 150 EXP
                $user->save();

                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Lulus Ujian Akhir! 🎓',
                    'description' => "Selamat! Kamu telah lulus ujian akhir kelas '{$userCourse->title}' dengan nilai {$score}%. Dapatkan sertifikat fokusmu sekarang!",
                    'type' => 'learning',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'passed' => $passed,
            'score' => $score,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
        ]);
    }
}
