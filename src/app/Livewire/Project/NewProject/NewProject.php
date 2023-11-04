<?php declare(strict_types=1);

namespace App\Livewire\Project\NewProject;

use App\Livewire\Utils\Label\SelectLabelPresenter;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\SortLabelPresenter;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\CreateProjectCommand;
use App\UseCases\Project\CreateProjectUseCase;


final class NewProject extends Component
{
    #[Rule('required|string|max:50')]
    public $projectName = '';

    #[Rule('required|string')]
    public $name = '';

    #[Rule('required|string')]
    public $content = '';

    #[Rule('required|string')]
    public $selectedLabel;

    public Collection $label;
    public Collection $LABELS;

    private readonly CreateProjectUseCase $createProject;
    private readonly SelectLabelPresenter $presenter;

    public function boot(
        CreateProjectUseCase $createProject,
        SelectLabelPresenter $presenter
    ) {
        $this->createProject = $createProject;
        $this->presenter     = $presenter;
    }

    public function mount()
    {
        $this->label         = $this->presenter->none();
        $this->selectedLabel = $this->label->get('text');
        $this->LABELS        = $this->presenter->labels();
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
        return $this->label->get('text') === $label->get('text');
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
            $command = new CreateProjectCommand(
                projectName: $this->projectName,
                label: $this->presenter->of($this->selectedLabel),
                name: $this->name,
                content: $this->content
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
    public function update(string $selected): void
    {        
        try {
            $label = $this->presenter->change($this->label, $selected);

            $this->label = $this->presenter->toViewData($label);
            $this->selectedLabel = $this->label->get('text');

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}