<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\ProjectName;

use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Form;


final class ProjectNameForm extends Form
{    
    #[Rule('string|required|max:50')]
    public string $projectName;

    #[Rule('bool|required')]
    public bool $isComplete;

    public function setProperty(Project $project)
    {
        $this->projectName = $project->project_name;
        $this->isComplete  = $project->is_complete;
    }
}
