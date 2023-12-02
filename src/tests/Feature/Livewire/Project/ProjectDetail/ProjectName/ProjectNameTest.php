<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail\ProjectName;

use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\Project\ProjectDetail\ProjectName\ProjectName;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Utils\Message\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectNameTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->assertSeeLivewire(ProjectName::class);
    }

    public function test_アップデート後にdispatchを発行するか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory())
            ->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->set('form.projectName', 'updatedProjectName')
            ->call('update')
            ->assertSet('form.projectName', 'updatedProjectName')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray())
            ->assertDispatched('close-project-name-input');
    }

    public function test_プロジェクトを完了後にプロジェクト一覧画面に移動するか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->has(Task::factory())
            ->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->call('complete')
            ->assertRedirect(Projects::class);
    }

    public function test_プロジェクトを未完了状態にした後にdispatchを発行するか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $project = Project::factory()
            ->state([
                'user_id'     => $user->id,
                'is_complete' => true
            ])
            ->has(Task::factory())
            ->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->assertSet('form.isComplete', true)
            ->call('incomplete')
            ->assertSet('form.isComplete', false)
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}