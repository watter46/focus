<?php declare(strict_types=1);

namespace App\UseCases\Project\SortProjects;

use App\Livewire\Utils\Label\DisplayLabelPresenter;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Project;
use App\UseCases\Project\SortProjects\SortProjectsCommand;


final readonly class SortProjectsUseCase
{
    public function __construct(private readonly DisplayLabelPresenter $presenter)
    {
        //
    }

    public function execute(SortProjectsCommand $command): LengthAwarePaginator
    {
        $project = Project::with('tasks:project_id')
                        ->progressIs($command->process())
                        ->labelIs($command->label())
                        ->paginate(5);
        
        $project
            ->getCollection()
            ->transform(function (Project $project) {
                $project->labelData = $this->presenter->toViewData($project->label);
                
                unset($project->label);
                
                return $project;
            });

        return $project;
    }
}