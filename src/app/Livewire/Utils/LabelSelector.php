<?php declare(strict_types=1);

namespace App\Livewire\Utils;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelInterface;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\FetchProject\FetchProjectUseCase;
use App\UseCases\Project\UpdateLabel\UpdateLabelUseCase;
use App\UseCases\Project\ProjectCommand;


final class LabelSelector extends Component
{
    #[Locked]
    public string $projectId;
    
    public string $title;

    public Collection $label;
    public Collection $LABELS;

    private readonly FetchProjectUseCase $fetchProject;
    private readonly UpdateLabelUseCase  $updateLabel;
    private readonly LabelInterface $presenter;

    public function boot(
        FetchProjectUseCase $fetchProject,
        UpdateLabelUseCase  $updateLabel,
        LabelCommand $command
    ) {
        $this->fetchProject = $fetchProject;
        $this->updateLabel  = $updateLabel;
        
        $this->presenter = $command->execute(PurposeType::select);
    }
    
    public function mount()
    {
        $command = new ProjectCommand($this->projectId);
        
        $project = $this->fetchProject->execute($command);

        $this->LABELS = $this->presenter->labels();
        $this->label  = $this->presenter->toViewData($project->label);
    }
    
    public function render()
    {
        return view('livewire.utils.label-selector');
    }
    
    /**
     * ラベルが同じか判定する
     *
     * @param  Collection $label
     * @return bool
     */
    public function isSame(Collection $label): bool
    {
        return $this->label->get('text') === $label->get('text');
    }
    
    /**
     * ラベルをアップデートする
     *
     * @param  string $selectedLabel
     * @return void
     */
    public function updateLabel(string $selectedLabel): void
    {
        try {
            $label = $this->presenter->change($this->label, $selectedLabel);

            $command = new ProjectCommand($this->projectId, label: $label);
            
            $project = $this->updateLabel->execute($command);

            $this->label = $this->presenter->toViewData($project->label);

            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}