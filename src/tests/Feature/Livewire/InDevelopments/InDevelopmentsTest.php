<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\InDevelopments;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\InDevelopments\InDevelopments;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\StartDevelopmentUseCase;


class InDevelopmentsTest extends TestCase
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
            ->state(new Sequence([
                'project_name' => 'projectName Test',
                'user_id'      => $user->id
            ]))
            ->has(Task::factory(3)
                ->state(new Sequence(
                    ['name' => 'name1'],
                    ['name' => 'name2'],
                    ['name' => 'name3'],
                )))
            ->create();
        
        $selectedIdList = $this->project
            ->load('tasks')
            ->tasks
            ->map(fn (Task $task) => $task->id)
            ->toArray();
                    
        /** @var StartDevelopmentUseCase $startDevelopment */
        $startDevelopment = app(StartDevelopmentUseCase::class);

        $startDevelopment->execute(DevelopmentCommand::start(
            $this->project->id,
            20,
            $selectedIdList
        ));

        $this->component = Livewire::test(InDevelopments::class);
    }

    public function test_レンダリングされるか()
    {
        $this->component
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame('projectName Test', $projects->first()->project_name);

                return true;
            });
    }

    public function test_Development画面へ移動する()
    {
        $this->component
            ->call('toDevelopment', $this->project->id)
            ->assertRedirect("/developments/{$this->project->id}");
    }
}
