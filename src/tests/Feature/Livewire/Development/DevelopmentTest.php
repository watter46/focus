<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Str;

use App\Models\Development as EqDevelopment;
use App\Models\Project;
use App\Models\User;
use App\Livewire\Development\Development;
use App\UseCases\Development\Domain\DevelopmentEntity;


class DevelopmentTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)->create($project)->toModel();
        
        Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->assertSeeLivewire(Development::class);
    }

    public function test_開発をスタートできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
        
        Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->dispatch('timer-started', 15, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 15);
                $this->assertSame($development->remaining_time, 15);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $project->id);

                return true;
            });
    }

    public function test_開発をストップできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->freezeTime();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $project->id);

                return true;
            });

        $started
            ->dispatch('timer-stopped', 10)
            ->assertViewHas('development', function (EqDevelopment $development) {
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 10);

                return true;
            });
    }

    public function test_開発を完了できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->freezeTime();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $project->id);

                return true;
            });

        $started
            ->dispatch('timer-completed')
            ->assertViewHas('development', function (EqDevelopment $development) {
                $this->assertTrue($development->is_complete);
                $this->assertSame($development->remaining_time, 0);
                $this->assertSame($development->finished_at->toDateString(), now()->toDateString());
                
                return true;
            })
            ->assertDispatched('on-start-break-time');
    }

    public function test_開発を途中でやめれるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->freezeTime();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $project->id);

                return true;
            });

        $started
            ->dispatch('timer-cleared')
            ->assertViewHas('development', function (EqDevelopment $development) {
                $this->assertTrue($development->is_complete);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->finished_at->toDateString(), now()->toDateString());

                return true;
            })
            ->assertDispatched('development-finished')
            ->assertDispatched('initialize');
    }

    public function test_再開発できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->freezeTime();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = Livewire::test(Development::class, ['projectId' => $project->id])
            ->assertSet('projectId', $project->id)
            ->assertSet('development', $development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $project->id);

                return true;
            });

        $started
            ->dispatch('break-time-finished', $project->id)
            ->assertViewHas('development', function (EqDevelopment $development) use ($project, $selectedIdList) {
                $this->assertSame($development->project_id, $project->id);
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);

                return true;
            })
            ->assertDispatched(
                'on-repeat-timer',
                defaultTime: 20,
                selectedIdList: $selectedIdList
            );
    }
}