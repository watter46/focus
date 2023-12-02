<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\TaskSelector;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Livewire\Development\TaskSelector\ChangeTask;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\StartDevelopmentUseCase;


class ChangeTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory(3)
                ->state(new Sequence(
                    ['name' => 'name1', 'is_complete' => false],
                    ['name' => 'name2', 'is_complete' => true],
                    ['name' => 'name3', 'is_complete' => false],
                )))
            ->create();
        
        $selectedIdList = $project
            ->load('tasks')
            ->tasks
            ->map(fn (Task $task) => $task->id)
            ->toArray();

        $startDevelopment = app(StartDevelopmentUseCase::class);

        $development = $startDevelopment->execute(DevelopmentCommand::start(
            $project->id,
            20,
            $selectedIdList
        ));
        
        Livewire::test(ChangeTask::class, [
            'developmentId' => $development->id,
        ])
        ->assertSeeLivewire(ChangeTask::class);
    }

    public function test_タスクIDを追加、削除できるか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory(3)
                ->state(new Sequence(
                    ['name' => 'name1', 'is_complete' => false],
                    ['name' => 'name2', 'is_complete' => true],
                    ['name' => 'name3', 'is_complete' => false],
                )))
            ->create();
        
        $selectedIdList = $project
            ->load('tasks')
            ->tasks
            ->map(fn (Task $task) => $task->id)
            ->toArray();

        $startDevelopment = app(StartDevelopmentUseCase::class);

        $development = $startDevelopment->execute(DevelopmentCommand::start(
            $project->id,
            20,
            $selectedIdList
        ));
        
        $additionalTaskId = (string) Str::ulid();
        
        Livewire::test(ChangeTask::class, [
            'developmentId' => $development->id
        ])
        ->call('change',$additionalTaskId, true)
        ->assertViewHas('additionalIdList', function (array $list) {
            $this->assertEquals(1, count($list));

            return true;
        })
        ->call('change', $additionalTaskId, false)
        ->assertSet('additionalIdList', []);
    }

    public function test_保存できるか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory(3)
                ->state(new Sequence(
                    ['name' => 'name1', 'is_complete' => false],
                    ['name' => 'name2', 'is_complete' => true],
                    ['name' => 'name3', 'is_complete' => false],
                )))
            ->create();
        
        $taskId = $project
            ->load('tasks')
            ->tasks
            ->first()
            ->id;

        $startDevelopment = app(StartDevelopmentUseCase::class);

        $development = $startDevelopment->execute(DevelopmentCommand::start(
            $project->id,
            20,
            [$taskId]
        ));

        $lastTaskId = $project
            ->tasks
            ->last()
            ->id;
        
        Livewire::test(ChangeTask::class, [
            'developmentId' => $development->id
        ])
        ->set('additionalIdList', [$lastTaskId])
        ->call('save')
        ->assertDispatched('refetch')
        ->assertDispatched('close-change-task')
        ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}
