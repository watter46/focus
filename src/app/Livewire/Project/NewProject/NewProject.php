<?php declare(strict_types=1);

namespace App\Livewire\Project\NewProject;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Project\CreateProjectUseCase;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelInterface;


final class NewProject extends Component
{
    public NewProjectForm $form;

    private readonly CreateProjectUseCase $createProject;
    private readonly LabelInterface $presenter;

    public function boot(
        CreateProjectUseCase $createProject,
        LabelCommand $command
    ) {
        $this->createProject = $createProject;
        
        $this->presenter = $command->execute(PurposeType::select);
    }

    public function mount()
    {
        $this->form->label  = $this->presenter->defaultLabel();
        $this->form->LABELS = $this->presenter->labels();
        $this->form->selectedLabel = $this->form->label->get('text');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.project.new-project.new-project');
    }
    
    /**
     * ラベルが同じか判定する
     *
     * @param  Collection $label
     * @return bool
     */
    public function isSame(Collection $label): bool
    {
        return $this->form->label->get('text') === $label->get('text');
    }

    /**
     * 新規プロジェクトを作成する
     *
     * @return void
     */
    public function create(): void
    {
        $this->validate();
        
        try {
            $command = ProjectCommand::newProject(
                projectName: $this->form->projectName,
                label:       LabelType::from($this->form->selectedLabel),
                name:        $this->form->name,
                content:     $this->form->content
            );
            
            $project = $this->createProject->execute($command);

            $this->redirectRoute('project.detail', [
                'projectId' => $project->id
            ]);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
    
    /**
     * ラベルを更新する
     *
     * @param  string $selected
     * @return void
     */
    public function updateLabel(string $selected): void
    {        
        try {
            $label = $this->presenter->change($this->form->label, $selected);

            $this->form->label = $this->presenter->toViewData($label);
            $this->form->selectedLabel = $this->form->label->get('text');

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}