<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
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

    public function test_user_can_create_schedule_with_standard_time(): void
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
        $response->assertJsonPath('schedule.start_time', '08:00');
        $response->assertJsonPath('schedule.end_time', '09:30');

        $this->assertDatabaseHas('schedules', [
            'user_id' => $user->id,
            'title' => 'Belajar HTML Dasar',
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);
    }

    public function test_user_can_create_schedule_with_seconds_time_normalized(): void
    {
        $user = $this->createOnboardedUser();

        // Pass '08:00:00' and '09:30:00' which would normally fail validation date_format:H:i
        $response = $this->actingAs($user)->postJson('/jadwal', [
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Lanjutan',
            'description' => 'Mempelajari form HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('schedule.start_time', '08:00');
        $response->assertJsonPath('schedule.end_time', '09:30');

        $this->assertDatabaseHas('schedules', [
            'user_id' => $user->id,
            'title' => 'Belajar HTML Lanjutan',
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);
    }

    public function test_user_can_update_schedule_with_seconds_time_normalized(): void
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
            'start_time' => '10:15:00',
            'end_time' => '11:45:00',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('schedule.start_time', '10:15');
        $response->assertJsonPath('schedule.end_time', '11:45');

        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'title' => 'Belajar HTML Terupdate',
            'start_time' => '10:15',
            'end_time' => '11:45',
        ]);
    }

    public function test_user_cannot_update_other_users_schedule(): void
    {
        $user1 = $this->createOnboardedUser();
        $user2 = $this->createOnboardedUser();

        $schedule = Schedule::create([
            'user_id' => $user1->id,
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Lanjutan',
            'description' => 'Mempelajari form HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        $response = $this->actingAs($user2)->putJson("/jadwal/{$schedule->id}", [
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Terupdate',
            'description' => 'Mempelajari form HTML terupdate',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '10:15',
            'end_time' => '11:45',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_schedule(): void
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
        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_schedule(): void
    {
        $user1 = $this->createOnboardedUser();
        $user2 = $this->createOnboardedUser();

        $schedule = Schedule::create([
            'user_id' => $user1->id,
            'topic' => 'Front End',
            'course' => 'Dasar HTML',
            'title' => 'Belajar HTML Lanjutan',
            'description' => 'Mempelajari form HTML',
            'routine_type' => 'Harian',
            'routine_config' => ['days' => ['Sen', 'Sel']],
            'start_time' => '08:00',
            'end_time' => '09:30',
        ]);

        $response = $this->actingAs($user2)->deleteJson("/jadwal/{$schedule->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
        ]);
    }
}
