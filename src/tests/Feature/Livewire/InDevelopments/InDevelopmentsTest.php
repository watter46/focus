<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\InDevelopments;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\InDevelopments\InDevelopments;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\StartDevelopmentUseCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class InDevelopmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                    ->state(new Sequence(['project_name' => 'projectName Test']))
                    ->has(Task::factory(3)
                        ->state(new Sequence(
                            ['name' => 'name1'],
                            ['name' => 'name2'],
                            ['name' => 'name3'],
                        )))
                    ->create();

        $selectedIdList = $project
            ->load('tasks')
            ->tasks
            ->map(fn (Task $task) => $task->id)
            ->toArray();
                    
        /** @var StartDevelopmentUseCase $startDevelopment */
        $startDevelopment = app(StartDevelopmentUseCase::class);

        $startDevelopment->execute(DevelopmentCommand::start(
            $project->id,
            20,
            $selectedIdList
        ));

        Livewire::test(InDevelopments::class)
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame('projectName Test', $projects->first()->project_name);

                return true;
            });
    }

    public function test_Development画面へ移動する()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                    ->state(new Sequence(['project_name' => 'projectName Test']))
                    ->has(Task::factory(3)
                        ->state(new Sequence(
                            ['name' => 'name1'],
                            ['name' => 'name2'],
                            ['name' => 'name3'],
                        )))
                    ->create();
        
        Livewire::test(InDevelopments::class)
            ->call('toDevelopment', $project->id)
            ->assertRedirect("/developments/{$project->id}");
    }
}
