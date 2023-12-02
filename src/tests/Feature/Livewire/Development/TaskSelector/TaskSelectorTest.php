<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Development\TaskSelector;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Livewire\Development\TaskSelector\TaskSelector;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


class TaskSelectorTest extends TestCase
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
                    ['name' => 'name1'],
                    ['name' => 'name2'],
                    ['name' => 'name3'],
                )))
            ->create();

        /** @var DevelopmentFactory $factory */
        $factory = app(DevelopmentFactory::class);

        /** @var DevelopmentModelBuilder $builder */
        $builder = app(DevelopmentModelBuilder::class);
        
        $entity = $factory->create($project);

        $development = $builder->toModel($entity);

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