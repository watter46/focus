<?php declare(strict_types=1);

namespace App\Livewire\Project\NewProject;

use Illuminate\Support\Collection;
use Livewire\Attributes\Rule;
use Livewire\Form;


final class NewProjectForm extends Form
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
}