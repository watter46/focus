<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\TaskSelector;

use App\Livewire\Development\TaskSelector\InprogressTasks;
use App\Livewire\Utils\Message\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\StartDevelopmentUseCase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


class InprogressTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
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

        Livewire::test(InprogressTasks::class, [
            'developmentId' => $development->id,
            'projectId'     => $development->project_id
        ])
        ->assertStatus(200)
        ->assertSee('name1')
        ->assertSee('name2')
        ->assertSee('name3');
    }

    public function test_タスクを追加できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
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

        Livewire::test(InprogressTasks::class, [
            'developmentId' => $development->id,
            'projectId'     => $development->project_id
        ])
        ->set('name', 'name4')
        ->set('content', 'content4')
        ->dispatch('add')
        ->assertDispatched('notify', message: Message::createSavedMessage()->toArray())
        ->assertSet('name', '')
        ->assertSet('content', '');

        $this->assertDatabaseHas('tasks', [
            'name' => 'name4',
            'content' => 'content4',
        ]);
    }
}
