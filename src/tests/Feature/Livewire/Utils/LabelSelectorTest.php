<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Utils;

use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\User;
use App\Livewire\Utils\LabelSelector;
use App\Livewire\Utils\Message\Message;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelPresenterResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;


class LabelSelectorTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->create();

        Livewire::test(LabelSelector::class, ['projectId' => $project->id])
            ->assertSeeLivewire(LabelSelector::class);
    }

    public function test_アップデート後にdispatchされるか()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);

        $project = Project::factory()
            ->state(['user_id' => $user->id])
            ->create();

        $command = new LabelCommand(new LabelPresenterResolver);

        $selectLabelPresenter = $command->execute(PurposeType::select);

        Livewire::test(LabelSelector::class, ['projectId' => $project->id])
            ->set('label', $selectLabelPresenter->defaultLabel())
            ->call('updateLabel', 'Todo')
            ->assertSet('label', $selectLabelPresenter->toViewData(LabelType::Todo))
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}