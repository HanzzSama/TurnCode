<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Submateri;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningTest extends TestCase
{
    use RefreshDatabase;

    private function createOnboardedUser()
    {
        return User::factory()->create([
            'onboarding_completed' => true,
            'interest' => 'Front End',
            'focus' => 'HTML',
        ]);
    }

    public function test_submateri_quiz_page_renders_successfully(): void
    {
        $user = $this->createOnboardedUser();

        $course = Course::create([
            'title' => 'HTML Dasar',
            'description' => 'Kelas belajar HTML dasar',
            'icon' => '🎨',
            'color' => '#3b82f6',
        ]);

        $submateri = Submateri::create([
            'course_id' => $course->id,
            'title' => 'HTML',
            'icon' => '🌐',
            'order' => 1,
            'status' => 'published',
        ]);

        $chapter = Chapter::create([
            'submateri_id' => $submateri->id,
            'title' => 'Tag Dasar',
            'order' => 1,
            'status' => 'published',
        ]);

        $lesson = Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Mengenal Tag p',
            'content' => 'Paragraf tag',
            'order' => 1,
            'status' => 'published',
        ]);

        $quiz1 = Quiz::create([
            'lesson_id' => $lesson->id,
            'type' => 'text',
            'question' => 'Apa tag untuk paragraf?',
            'options' => ['p', 'div', 'span', 'section'],
            'correct_answer' => 'p',
            'explanation' => 'Tag p digunakan untuk mendefinisikan paragraf.',
        ]);

        $quiz2 = Quiz::create([
            'lesson_id' => $lesson->id,
            'type' => 'puzzle',
            'question' => 'Urutkan kode berikut:',
            'options' => ['let x = 5;', 'console.log(x);'],
            'correct_answer' => json_encode(['let x = 5;', 'console.log(x);']),
            'explanation' => 'Mendeklarasikan variabel kemudian mencetaknya.',
        ]);

        // Complete the lesson to allow accessing the quiz
        $user->lessons()->attach($lesson->id);

        $response = $this->actingAs($user)->get(route('submateris.quiz.show', $submateri->id));

        $response->assertStatus(200);
        $response->assertSee('Apa tag untuk paragraf?');
        $response->assertSee('Urutkan kode berikut:');
    }

    public function test_submateri_quiz_submission(): void
    {
        $user = $this->createOnboardedUser();

        $course = Course::create([
            'title' => 'HTML Dasar',
            'description' => 'Kelas belajar HTML dasar',
            'icon' => '🎨',
            'color' => '#3b82f6',
        ]);

        $submateri = Submateri::create([
            'course_id' => $course->id,
            'title' => 'HTML',
            'icon' => '🌐',
            'order' => 1,
            'status' => 'published',
        ]);

        $chapter = Chapter::create([
            'submateri_id' => $submateri->id,
            'title' => 'Tag Dasar',
            'order' => 1,
            'status' => 'published',
        ]);

        $lesson = Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Mengenal Tag p',
            'content' => 'Paragraf tag',
            'order' => 1,
            'status' => 'published',
        ]);

        $quiz1 = Quiz::create([
            'lesson_id' => $lesson->id,
            'type' => 'text',
            'question' => 'Apa tag untuk paragraf?',
            'options' => ['p', 'div', 'span', 'section'],
            'correct_answer' => 'p',
            'explanation' => 'Tag p digunakan untuk mendefinisikan paragraf.',
        ]);

        $quiz2 = Quiz::create([
            'lesson_id' => $lesson->id,
            'type' => 'puzzle',
            'question' => 'Urutkan kode berikut:',
            'options' => ['let x = 5;', 'console.log(x);'],
            'correct_answer' => json_encode(['let x = 5;', 'console.log(x);']),
            'explanation' => 'Mendeklarasikan variabel kemudian mencetaknya.',
        ]);

        // Complete the lesson to allow accessing the quiz
        $user->lessons()->attach($lesson->id);

        // Submit correct answers (quiz1 is MCQ, quiz2 is puzzle)
        $response = $this->actingAs($user)->postJson(route('submateris.quiz.submit', $submateri->id), [
            'answers' => [
                $quiz1->id => 'p',
                $quiz2->id => ['let x = 5;', 'console.log(x);']
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'passed' => true,
            'correct_count' => 2,
            'total_questions' => 2
        ]);

        // Submit incorrect answers
        $response = $this->actingAs($user)->postJson(route('submateris.quiz.submit', $submateri->id), [
            'answers' => [
                $quiz1->id => 'div',
                $quiz2->id => ['console.log(x);', 'let x = 5;']
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'passed' => false,
            'correct_count' => 0,
            'total_questions' => 2
        ]);
    }
}
