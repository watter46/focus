<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\TaskSelector;

use App\Livewire\Development\TaskSelector\InprogressTasks;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Livewire\Development\TaskSelector\TaskSelector;
use App\UseCases\Development\Domain\DevelopmentEntity;


class TaskSelectorTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                    ->has(Task::factory(3)
                        ->state(new Sequence(
                            ['name' => 'name1'],
                            ['name' => 'name2'],
                            ['name' => 'name3'],
                        )))
                    ->create();

        $development = (new DevelopmentEntity)->create($project)->toModel();

        Livewire::test(TaskSelector::class, [
            'projectId'     => $project->id,
            'developmentId' => $development->id,
            'isStart'       => $development->is_start
        ])
        ->assertSeeLivewire(TaskSelector::class)
        ->assertSee('name1')
        ->assertSee('name2')
        ->assertSee('name3');
    }
}