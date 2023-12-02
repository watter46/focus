<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Str;

use App\Models\Development as EqDevelopment;
use App\Models\Project;
use App\Models\User;
use App\Livewire\Development\Development;
use App\Models\Task;
use App\UseCases\Development\Domain\Development as DevelopmentEntity;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


class DevelopmentTest extends TestCase
{
    use RefreshDatabase;

    private EqDevelopment $development;
    private Project       $project;
    
    private $component;

    public function setUp(): void
    {
        Parent::setUp();
        
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory()->state(['name' => 'task1']))
            ->create();
        
        $taskId = $this->project->load('tasks')->tasks->sole()->id;
            
        /** @var DevelopmentFactory $factory */
        $factory = app(DevelopmentFactory::class);

        /** @var DevelopmentModelBuilder $builder */
        $builder = app(DevelopmentModelBuilder::class);
        
        $entity = $factory->create($this->project);

        $this->development = $builder->toModel($entity);
        
        // $this->development = EqDevelopment::factory()
        //     ->state([
        //         'project_id'       => $this->project->id,
        //         'selected_id_list' => [$taskId]
        //     ])
        //     ->for($this->project)
        //     ->create();

        $this->component = Livewire::test(Development::class, ['projectId' => $this->project->id]);
    }
    
    
    public function test_レンダリングされるか()
    {                
        $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->assertSeeLivewire(Development::class);
    }

    public function test_開発をスタートできるか()
    {
        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->dispatch('timer-started', 15, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 15);
                $this->assertSame($development->remaining_time, 15);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $this->project->id);

                return true;
            });
    }

    public function test_開発をストップできるか()
    {
        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $this->project->id);

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
        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $this->project->id);

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
        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $this->project->id);

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
        $this->freezeTime();

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];

        $started = $this->component
            ->assertSet('projectId', $this->project->id)
            ->assertSet('development', $this->development)
            ->dispatch('timer-started', 20, $selectedIdList)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at->toDateString(), now()->toDateString());
                $this->assertSame($development->finished_at, null);
                $this->assertSame($development->selected_id_list, $selectedIdList);
                $this->assertSame($development->project_id, $this->project->id);

                return true;
            });

        $started
            ->dispatch('break-time-finished', $this->project->id)
            ->assertViewHas('development', function (EqDevelopment $development) use ($selectedIdList) {
                $this->assertSame($development->project_id, $this->project->id);
                $this->assertTrue($development->is_start);
                $this->assertFalse($development->is_complete);
                $this->assertSame($development->default_time, 20);
                $this->assertSame($development->remaining_time, 20);
                $this->assertSame($development->started_at, null);
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