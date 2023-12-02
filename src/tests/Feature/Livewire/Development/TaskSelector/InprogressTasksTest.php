<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\TaskSelector;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Livewire\Development\TaskSelector\InprogressTasks;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\StartDevelopmentUseCase;


class InprogressTasksTest extends TestCase
{
    use RefreshDatabase;

    private Project $project;
    private $component;

    public function setUp(): void
    {
        Parent::setUp();

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory(3)
                ->state(new Sequence(
                    ['name' => 'name1', 'is_complete' => false],
                    ['name' => 'name2', 'is_complete' => true],
                    ['name' => 'name3', 'is_complete' => false],
                )))
            ->create();

            $selectedIdList = $this->project
            ->load('tasks')
            ->tasks
            ->map(fn (Task $task) => $task->id)
            ->toArray();

        $startDevelopment = app(StartDevelopmentUseCase::class);

        $development = $startDevelopment->execute(DevelopmentCommand::start(
            $this->project->id,
            20,
            $selectedIdList
        ));

        $this->component = Livewire::test(InprogressTasks::class, [
            'developmentId' => $development->id,
            'projectId'     => $development->project_id
        ]);
    }

    public function test_レンダリングされるか()
    {
        $this->component
            ->assertStatus(200)
            ->assertSee('name1')
            ->assertSee('name2')
            ->assertSee('name3');
    }

    public function test_タスクを追加できるか()
    {
        $this->component
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
