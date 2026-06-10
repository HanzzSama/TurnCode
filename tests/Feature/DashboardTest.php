<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Submateri;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private $feCourse;
    private $beCourse;
    private $feSubHTML;
    private $beSubPHP;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Front End Course & Submateri
        $this->feCourse = Course::create([
            'title' => 'Front End',
            'description' => 'Frontend course',
            'icon' => '🎨',
            'color' => '#3b82f6'
        ]);

        $this->feSubHTML = Submateri::create([
            'course_id' => $this->feCourse->id,
            'title' => 'HTML',
            'description' => 'HTML dasar',
            'icon' => '🌐',
            'order' => 1
        ]);

        $ch1 = Chapter::create([
            'submateri_id' => $this->feSubHTML->id,
            'title' => 'Dasar HTML',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $ch1->id,
            'title' => 'Membangun Struktur Web',
            'content' => 'HTML intro',
            'order' => 1
        ]);

        // 2. Create Back End Course & Submateri
        $this->beCourse = Course::create([
            'title' => 'Back End',
            'description' => 'Backend course',
            'icon' => '⚙️',
            'color' => '#10b981'
        ]);

        $this->beSubPHP = Submateri::create([
            'course_id' => $this->beCourse->id,
            'title' => 'PHP',
            'description' => 'PHP dasar',
            'icon' => '🐘',
            'order' => 1
        ]);

        $ch2 = Chapter::create([
            'submateri_id' => $this->beSubPHP->id,
            'title' => 'Sintaks PHP',
            'order' => 1
        ]);

        Lesson::create([
            'chapter_id' => $ch2->id,
            'title' => 'Variabel PHP',
            'content' => 'PHP intro',
            'order' => 1
        ]);
    }

    public function test_dashboard_displays_frontend_course_submaterials_for_frontend_focused_user(): void
    {
        $user = User::factory()->create([
            'onboarding_completed' => true,
            'interest' => 'web-dev',
            'focus' => 'frontend',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Front End');
        $response->assertSee('HTML');
        $response->assertSee('Membangun Struktur Web');

        // Should not see backend course materials
        $response->assertDontSee('PHP');
        $response->assertDontSee('Variabel PHP');
    }

    public function test_dashboard_displays_backend_course_submaterials_for_backend_focused_user(): void
    {
        $user = User::factory()->create([
            'onboarding_completed' => true,
            'interest' => 'web-dev',
            'focus' => 'backend',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Back End');
        $response->assertSee('PHP');
        $response->assertSee('Variabel PHP');

        // Should not see frontend course materials
        $response->assertDontSee('HTML');
        $response->assertDontSee('Membangun Struktur Web');
    }
}
