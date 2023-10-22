<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Project;
use App\Livewire\Utils\Label\Label;

final readonly class SortProjectsUseCase
{
    public function execute(Collection $options): LengthAwarePaginator
    {
        $project = Project::with('tasks:project_id')
                    ->progressIs($options->get('progress'))
                    ->labelIs($options->get('label'))
                    ->paginate(5);

        $project
        ->getCollection()
        ->transform(function ($project) {
            $project->label = Label::Display()->of($project->label);

            return $project;
        });
        
        return $project;
    }
}