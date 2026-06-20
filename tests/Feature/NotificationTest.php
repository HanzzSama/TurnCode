<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
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

    public function test_creating_schedule_creates_notification(): void
    {
        $user = $this->createOnboardedUser();

        $response = $this->actingAs($user)->postJson('/jadwal', [
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Dasar',
            'description' => 'Mempelajari tag dasar HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Jadwal Baru Dibuat 📅',
            'type' => 'schedule',
        ]);
    }

    public function test_updating_schedule_creates_notification(): void
    {
        $user = $this->createOnboardedUser();
        $schedule = Schedule::create([
            'user_id' => $user->id,
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Lanjutan',
            'description' => 'Mempelajari form HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        $response = $this->actingAs($user)->putJson("/jadwal/{$schedule->id}", [
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Terupdate',
            'description' => 'Mempelajari form HTML terupdate',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '10:15',
            'end_time' => '11:45',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Jadwal Diperbarui ⚙️',
            'type' => 'schedule',
        ]);
    }

    public function test_deleting_schedule_creates_notification(): void
    {
        $user = $this->createOnboardedUser();
        $schedule = Schedule::create([
            'user_id' => $user->id,
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Lanjutan',
            'description' => 'Mempelajari form HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        $response = $this->actingAs($user)->deleteJson("/jadwal/{$schedule->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Jadwal Dihapus 🗑️',
            'type' => 'schedule',
        ]);
    }

    public function test_updating_profile_creates_notification(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Profil Diperbarui 👤',
            'type' => 'profile',
        ]);
    }

    public function test_updating_password_creates_notification(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Password Diperbarui 🔒',
            'type' => 'profile',
        ]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 1',
            'description' => 'Desc 1',
            'type' => 'system',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 2',
            'description' => 'Desc 2',
            'type' => 'system',
        ]);

        $this->assertEquals(2, $user->notifications()->unread()->count());

        $response = $this->actingAs($user)->postJson('/api/notifications/read-all');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertEquals(0, $user->notifications()->unread()->count());
    }

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $user = User::factory()->create();

        $notif = Notification::create([
            'user_id' => $user->id,
            'title' => 'Notif 1',
            'description' => 'Desc 1',
            'type' => 'system',
        ]);

        $this->assertNull($notif->read_at);

        $response = $this->actingAs($user)->postJson("/api/notifications/{$notif->id}/read");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertNotNull($notif->fresh()->read_at);
    }

    public function test_user_cannot_mark_other_user_notification_as_read(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $notif = Notification::create([
            'user_id' => $user2->id,
            'title' => 'Notif 1',
            'description' => 'Desc 1',
            'type' => 'system',
        ]);

        $response = $this->actingAs($user1)->postJson("/api/notifications/{$notif->id}/read");

        $response->assertStatus(403);
        $this->assertNull($notif->fresh()->read_at);
    }

    public function test_quiz_completion_creates_notification(): void
    {
        $user = $this->createOnboardedUser();

        $course = \App\Models\Course::create([
            'title' => 'HTML Dasar',
            'description' => 'Course description',
        ]);

        $submateri = \App\Models\Submateri::create([
            'course_id' => $course->id,
            'title' => 'HTML',
            'status' => 'published',
        ]);

        $chapter = \App\Models\Chapter::create([
            'submateri_id' => $submateri->id,
            'title' => 'Chapter 1',
            'order' => 1,
            'status' => 'published',
        ]);

        $lesson = \App\Models\Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Tag Dasar HTML',
            'content' => 'Lesson content',
            'order' => 1,
            'status' => 'published',
        ]);

        $quiz = \App\Models\Quiz::create([
            'lesson_id' => $lesson->id,
            'question' => 'Apa tag untuk paragraph?',
            'options' => ['p', 'h1', 'div'],
            'correct_answer' => 'p',
            'explanation' => 'Tag p digunakan untuk membuat paragraf.',
        ]);

        // Complete the lesson to allow accessing the quiz
        $user->lessons()->attach($lesson->id);

        $response = $this->actingAs($user)->postJson(route('submateris.quiz.submit', $submateri->id), [
            'answers' => [
                $quiz->id => 'p'
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'passed' => true,
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Lulus Uji Pemahaman! 💡',
            'type' => 'learning',
        ]);
    }

    public function test_tier_promotion_creates_notification(): void
    {
        $user = User::factory()->create([
            'onboarding_completed' => true,
            'interest' => 'Front End',
            'focus' => 'HTML',
            'exp' => 95,
        ]);

        $this->assertEquals('Initiate', $user->tier);

        $response = $this->actingAs($user)->postJson('/api/user/add-exp');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'exp' => 100,
            'tier' => 'Explorer',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Naik Tier! 🏆',
            'type' => 'learning',
        ]);
    }
}

