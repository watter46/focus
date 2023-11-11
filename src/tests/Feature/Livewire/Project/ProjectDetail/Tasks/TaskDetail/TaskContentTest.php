<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\TaskContent;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\TaskContentPresenter;


class TaskContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {   
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(fn() => ['content' => 'test content']))
                        ->create();

        /** @var Task $task */
        $task = $project->incompleteTasks()->first();

        $converted = (app(TaskContentPresenter::class))->execute($task->content);

        Livewire::test(TaskContent::class, [
                'taskId'    => $task->id,
                'projectId' => $task->project_id,
                'content'   => $task->content
            ])
            ->assertSeeLivewire(TaskContent::class)
            ->assertViewHas('tasks', $converted);
    }
}