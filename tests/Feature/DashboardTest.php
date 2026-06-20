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
            'order' => 1,
            'status' => 'published'
        ]);

        $ch1 = Chapter::create([
            'submateri_id' => $this->feSubHTML->id,
            'title' => 'Dasar HTML',
            'order' => 1,
            'status' => 'published'
        ]);

        Lesson::create([
            'chapter_id' => $ch1->id,
            'title' => 'Membangun Struktur Web',
            'content' => 'HTML intro',
            'order' => 1,
            'status' => 'published'
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
            'order' => 1,
            'status' => 'published'
        ]);

        $ch2 = Chapter::create([
            'submateri_id' => $this->beSubPHP->id,
            'title' => 'Sintaks PHP',
            'order' => 1,
            'status' => 'published'
        ]);

        Lesson::create([
            'chapter_id' => $ch2->id,
            'title' => 'Variabel PHP',
            'content' => 'PHP intro',
            'order' => 1,
            'status' => 'published'
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
        $response->assertSee('collapsed-text">HTML', false);

        // Should not see backend course materials in materials list
        $response->assertDontSee('collapsed-text">PHP', false);

        // Check lessons on the course detail page
        $courseResponse = $this->actingAs($user)->get(route('courses.show', $this->feCourse->id));
        $courseResponse->assertStatus(200);
        $courseResponse->assertSee('Membangun Struktur Web');
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
        $response->assertSee('collapsed-text">PHP', false);

        // Should not see frontend course materials in materials list
        $response->assertDontSee('collapsed-text">HTML', false);

        // Check lessons on the course detail page
        $courseResponse = $this->actingAs($user)->get(route('courses.show', $this->beCourse->id));
        $courseResponse->assertStatus(200);
        $courseResponse->assertSee('Variabel PHP');
    }
}
