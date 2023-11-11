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
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->get("/projects/$project->id")
            ->assertSeeLivewire(ProjectDetail::class);
    }

    public function test_ProjectIdが設定されているか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(ProjectDetail::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id);
    }
}
