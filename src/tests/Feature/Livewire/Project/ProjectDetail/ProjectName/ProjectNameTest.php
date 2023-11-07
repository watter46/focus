<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail\ProjectName;

use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\Project\ProjectDetail\ProjectName\ProjectName;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Utils\Message\Message;
use App\Models\Project;
use App\Models\User;


class ProjectNameTest extends TestCase
{
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->assertSeeLivewire(ProjectName::class);
    }

    public function test_アップデート後にdispatchを発行するか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->set('form.projectName', 'updatedProjectName')
            ->call('update')
            ->assertSet('form.projectName', 'updatedProjectName')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray())
            ->assertDispatched('close-project-name-input');
    }

    public function test_プロジェクトを完了後にプロジェクト一覧画面に移動するか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->call('complete')
            ->assertRedirect(Projects::class);
    }

    public function test_プロジェクトを未完了状態にした後にdispatchを発行するか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
            ->state([
                'is_complete' => true
            ])
            ->create();

        Livewire::test(ProjectName::class, ['projectId' => $project->id])
            ->assertSet('form.isComplete', true)
            ->call('incomplete')
            ->assertSet('form.isComplete', false)
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}