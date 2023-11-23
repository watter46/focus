<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\Timer;

use App\Livewire\Development\Timer\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\Development\Timer\Timer;
use App\Models\Project;
use App\Models\User;
use App\UseCases\Development\Domain\DevelopmentEntity;
use Illuminate\Support\Str;

class TimerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        Livewire::test(Timer::class, [
            'projectId'     => $project->id,
            'defaultTime'   => $development->default_time,
            'remainingTime' => $development->remaining_time,
            'isStart'       => $development->is_start
        ])
        ->assertSet('status', (new Status)->toDisabled())
        ->assertSet('muteStart', true)
        ->assertSet('muteClear', true)
        ->assertSet('muteTimeSet', false)
        ->assertSet('selectedIdList', [])
        ->assertSeeLivewire(Timer::class);
    }

    public function test_スタートできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $rendered
            ->set('selectedIdList', $selectedIdList)
            ->call('start')
            ->assertSet('status', (new Status)->toRunning())
            ->assertSet('isStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', true)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertDispatched(
                'timer-started',
                defaultTime: 25,
                selectedIdList: $selectedIdList
            );
    }

    public function test_ストップできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $rendered
            ->set('selectedIdList', $selectedIdList)
            ->call('start')
            ->assertSet('status', (new Status)->toRunning())
            ->assertSet('isStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', true)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertDispatched(
                'timer-started',
                defaultTime: 25,
                selectedIdList: $selectedIdList
            );
        
        $started
            ->call('stop', 15)
            ->assertSet('status', (new Status)->toPaused())
            ->assertSet('isStart', true)
            ->assertSet('muteClear', false)
            ->assertSet('muteTimeSet', true)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertDispatched(
                'timer-stopped',
                15
            );
    }

    public function test_クリアできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $rendered
            ->set('selectedIdList', $selectedIdList)
            ->call('start')
            ->assertSet('status', (new Status)->toRunning())
            ->assertSet('isStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', true)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertDispatched(
                'timer-started',
                defaultTime: 25,
                selectedIdList: $selectedIdList
            );
        
        $started
            ->call('clear')
            ->assertDispatched('timer-cleared');
    }

    public function test_リピートできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $rendered
            ->set('selectedIdList', $selectedIdList)
            ->call('start')
            ->assertSet('status', (new Status)->toRunning())
            ->assertSet('isStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', true)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertDispatched(
                'timer-started',
                defaultTime: 25,
                selectedIdList: $selectedIdList
            );
        
        $started
            ->dispatch('on-repeat-timer', 20, $selectedIdList)
            ->assertSet('defaultTime', 20)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertSet('isStart', true)
            ->assertDispatched('on-reset-timer');
    }

    public function test_timerを強制終了できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $rendered
            ->dispatch('on-kill-timer')
            ->assertDispatched('timer-killed')
            ->assertSet('status', (new Status)->toDisabled())
            ->assertSet('isStart', false)
            ->assertSet('muteStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', false)
            ->assertSet('selectedIdList', []);
    }

    public function test_タイマーを初期化できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);
        
        $rendered
            ->dispatch('initialize')
            ->assertSet('status', (new Status)->toDisabled())
            ->assertSet('isStart', false)
            ->assertSet('muteStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', false)
            ->assertSet('selectedIdList', [])
            ->assertDispatched('on-reset-timer');
    }

    public function test_時間を設定できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);
        
        $rendered
            ->call('setTime', 30)
            ->assertSet('defaultTime', 30)
            ->assertSet('remainingTime', 30);
    }

    public function test_TaskIdを設定できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $development = (new DevelopmentEntity)
                        ->create($project)
                        ->toModel();

        $rendered = Livewire::test(Timer::class, [
                'projectId'     => $project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);

        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
        
        $rendered
            ->dispatch('setSelectedIdList', $selectedIdList)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertSet('status', (new Status)->toReady());

        $rendered
            ->dispatch('setSelectedIdList', [])
            ->assertSet('selectedIdList', [])
            ->assertSet('status', (new Status)->toDisabled());
    }
}