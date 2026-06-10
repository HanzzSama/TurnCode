<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
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

    public function test_lesson_page_with_quiz_renders_successfully(): void
    {
        $user = $this->createOnboardedUser();

        $course = Course::create([
            'title' => 'HTML Dasar',
            'description' => 'Kelas belajar HTML dasar',
            'icon' => '🎨',
            'color' => '#3b82f6',
        ]);

        $submateri = \App\Models\Submateri::create([
            'course_id' => $course->id,
            'title' => 'HTML',
            'icon' => '🌐',
            'order' => 1,
        ]);

        $chapter = Chapter::create([
            'submateri_id' => $submateri->id,
            'title' => 'Tag Dasar',
            'order' => 1,
        ]);

        $lesson = Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Mengenal Tag p',
            'content' => 'Paragraf tag',
            'order' => 1,
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'question' => 'Apa tag untuk paragraf?',
            'options' => ['p', 'div', 'span', 'section'],
            'correct_answer' => 'p',
            'explanation' => 'Tag p digunakan untuk mendefinisikan paragraf.',
        ]);

        $response = $this->actingAs($user)->get(route('lessons.show', $lesson->id));

        $response->assertStatus(200);
        $response->assertSee('Apa tag untuk paragraf?');
        $response->assertSee('p');
        $response->assertSee('div');
        $response->assertSee('span');
        $response->assertSee('section');
    }

    public function test_lesson_page_quiz_submission(): void
    {
        $user = $this->createOnboardedUser();

        $course = Course::create([
            'title' => 'HTML Dasar',
            'description' => 'Kelas belajar HTML dasar',
            'icon' => '🎨',
            'color' => '#3b82f6',
        ]);

        $submateri = \App\Models\Submateri::create([
            'course_id' => $course->id,
            'title' => 'HTML',
            'icon' => '🌐',
            'order' => 1,
        ]);

        $chapter = Chapter::create([
            'submateri_id' => $submateri->id,
            'title' => 'Tag Dasar',
            'order' => 1,
        ]);

        $lesson = Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Mengenal Tag p',
            'content' => 'Paragraf tag',
            'order' => 1,
        ]);

        $quiz = Quiz::create([
            'lesson_id' => $lesson->id,
            'question' => 'Apa tag untuk paragraf?',
            'options' => ['p', 'div', 'span', 'section'],
            'correct_answer' => 'p',
            'explanation' => 'Tag p digunakan untuk mendefinisikan paragraf.',
        ]);

        // Submit correct answer
        $response = $this->actingAs($user)->postJson(route('lessons.quiz.submit', $lesson->id), [
            'quiz_id' => $quiz->id,
            'answer' => 'p',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'correct' => true,
            'explanation' => 'Tag p digunakan untuk mendefinisikan paragraf.',
        ]);

        // Verify lesson completion in database
        $this->assertTrue($user->lessons()->where('lesson_id', $lesson->id)->exists());

        // Submit incorrect answer
        $response = $this->actingAs($user)->postJson(route('lessons.quiz.submit', $lesson->id), [
            'quiz_id' => $quiz->id,
            'answer' => 'div',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'correct' => false,
        ]);
    }
}
