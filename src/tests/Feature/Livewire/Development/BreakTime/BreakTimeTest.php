<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\BreakTime;

use App\Livewire\Development\BreakTime\BreakTime;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


class BreakTimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(BreakTime::class, ['projectId' => $project->id])
            ->assertSet('breakTime', 5)
            ->assertStatus(200);
    }

    public function test_リピートされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(BreakTime::class, ['projectId' => $project->id])
            ->call('repeat')
            ->assertDispatched('break-time-finished', $project->id);
    }

    public function test_タスク選択画面に移動するか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(BreakTime::class, ['projectId' => $project->id])
            ->call('fresh')
            ->assertRedirect("/developments/{$project->id}");
    }
}
