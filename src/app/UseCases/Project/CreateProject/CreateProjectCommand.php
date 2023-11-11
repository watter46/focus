<?php declare(strict_types=1);

namespace App\UseCases\Project\CreateProject;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\UseCases\Task\RegisterTask\TaskInProject;


final readonly class CreateProjectCommand
{
    public function __construct(
        private string $projectName,
        private string $label,
        private string $name,
        private string $content 
    ) {
        //
    }

    public function projectName(): string
    {
        return $this->projectName;
    }

    public function label(): LabelType
    {
        return LabelType::tryFrom($this->label);
    }

    public function taskInProjectCommand(): TaskInProject
    {
        return new TaskInProject(
            name: $this->name,
            content: $this->content
        );
    }
}