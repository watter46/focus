<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\BreakTime;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Livewire\Development\BreakTime\BreakTime;


class BreakTimeTest extends TestCase
{
    use RefreshDatabase;

    private Project $project;
    private $component;

    public function setUp(): void
    {
        Parent::setUp();
        
        $user = User::factory()->create();
        
        $this->actingAs($user);

        $this->project = Project::factory()->state(['user_id' => $user->id])->create();

        $this->component = Livewire::test(BreakTime::class, ['projectId' => $this->project->id]);
    }

    public function test_レンダリングされるか()
    {
        $this->component
            ->assertSet('breakTime', 5)
            ->assertStatus(200);
    }

    public function test_リピートされるか()
    {
        $this->component
            ->call('repeat')
            ->assertDispatched('break-time-finished', $this->project->id);
    }

    public function test_タスク選択画面に移動するか()
    {
        $this->component
            ->call('fresh')
            ->assertRedirect("/developments/{$this->project->id}");
    }
}
