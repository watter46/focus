<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;


use App\Models\Project;
use App\Models\User;
use App\Livewire\Project\ProjectDetail\ProjectDetail;


class ProjectDetailTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングしているか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->create();

        $this->get("/projects/$project->id")
            ->assertSeeLivewire(ProjectDetail::class);
    }

    public function test_ProjectIdが設定されているか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->create();

        Livewire::test(ProjectDetail::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id);
    }
}
