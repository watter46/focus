<?php declare(strict_types=1);

namespace App\UseCases\Project\SortProjects;

use App\Livewire\Project\Projects\Options;
use App\Livewire\Project\Projects\Progress\ProgressType;
use App\Livewire\Utils\Label\Enum\LabelType;


final readonly class SortProjectsCommand
{
    public function __construct(private readonly Options $options)
    {
        //
    }

    public function label(): LabelType
    {
        return $this->options->getLabel();
    }
    
    public function process(): ProgressType
    {
        return $this->options->getProgress();
    }
}