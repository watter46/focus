<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\NewProject;

use Livewire\Livewire;
use Tests\TestCase;

use App\Models\User;
use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelPresenterResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class NewProjectTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());
        
        $this->get('/projects/new')
            ->assertSeeLivewire(NewProject::class);
    }

    public function test_Projectを作成したらリダイレクトするか()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(NewProject::class)
            ->set('form.projectName', 'test project name')
            ->set('form.name', 'test')
            ->set('form.content', 'test')
            ->call('create')
            ->assertRedirect();
    }

    public function test_Projectを更新できるか()
    {
        $this->actingAs(User::factory()->create());

        $command = new LabelCommand(new LabelPresenterResolver);

        $sortLabelPresenter = $command->execute(PurposeType::select);

        $noneLabel = $sortLabelPresenter->toViewData(LabelType::None);
        $todoLabel = $sortLabelPresenter->toViewData(LabelType::Todo);

        Livewire::test(NewProject::class)
            ->set('form.projectName', 'test project name')
            ->set('form.name', 'test')
            ->set('form.content', 'test')
            ->set('form.selectedLabel', 'None')
            ->set('form.label', $noneLabel)
            ->call('updateLabel', 'Todo')
            ->assertSet('form.selectedLabel', 'Todo')
            ->assertSet('form.label', $todoLabel);
    }
}
