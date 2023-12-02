<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\Timer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Livewire\Development\Timer\Status;
use App\Livewire\Development\Timer\Timer;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


class TimerTest extends TestCase
{
    use RefreshDatabase;

    private $component;
    private Project $project;

    public function setUp(): void
    {
        Parent::setUp();

        $user = User::factory()->create();
        
        $this->actingAs($user);

        $this->project = Project::factory()->state(['user_id' => $user->id])->create();

        /** @var DevelopmentFactory $factory */
        $factory = app(DevelopmentFactory::class);

        /** @var DevelopmentModelBuilder $builder */
        $builder = app(DevelopmentModelBuilder::class);
        
        $entity = $factory->create($this->project);

        $development = $builder->toModel($entity);

        $this->component = Livewire::test(Timer::class, [
                'projectId'     => $this->project->id,
                'defaultTime'   => $development->default_time,
                'remainingTime' => $development->remaining_time,
                'isStart'       => $development->is_start
            ]);
    }
    
    public function test_レンダリングされるか()
    {
        $this->component
            ->assertSet('status', (new Status)->toDisabled())
            ->assertSet('muteStart', true)
            ->assertSet('muteClear', true)
            ->assertSet('muteTimeSet', false)
            ->assertSet('selectedIdList', [])
            ->assertSeeLivewire(Timer::class);
    }

    public function test_スタートできるか()
    {
        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $this->component
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
        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $this->component
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
        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $this->component
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
        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
            
        $started = $this->component
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
        $this->component
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
        $this->component
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
        $this->component
            ->call('setTime', 30)
            ->assertSet('defaultTime', 30)
            ->assertSet('remainingTime', 30);
    }

    public function test_TaskIdを設定できるか()
    {
        $taskId = (string) Str::ulid();
        $selectedIdList = [$taskId];
        
        $this->component
            ->dispatch('setSelectedIdList', $selectedIdList)
            ->assertSet('selectedIdList', $selectedIdList)
            ->assertSet('status', (new Status)->toReady());

        $this->component
            ->dispatch('setSelectedIdList', [])
            ->assertSet('selectedIdList', [])
            ->assertSet('status', (new Status)->toDisabled());
    }
}