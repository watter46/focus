<?php declare(strict_types=1);

namespace App\Livewire\Project\NewProject;

use App\Livewire\Utils\Label\Label;
use App\UseCases\Project\CreateProjectUseCase;
use Illuminate\Support\Collection;
use Livewire\Attributes\Rule;


trait NewProjectFormableTrait
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

    public function bootNewProjectFormableTrait(CreateProjectUseCase $createProject)
    {
        $this->createProject = $createProject;
    }

    public function mountNewProjectFormableTrait()
    {
        $this->label         = Label::Select()->of('None');
        $this->selectedLabel = $this->label->get('text');
        $this->LABELS        = Label::Select()->labels();
    }
}