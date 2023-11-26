<?php declare(strict_types=1);

namespace App\UseCases\Project\Infrastructure;

use App\Models\Project;
use App\UseCases\Project\Domain\ProjectEntity;
use App\UseCases\Project\Domain\TaskIdList;
use App\UseCases\Project\ProjectCommand;


final readonly class ProjectFactory
{
    public function create(ProjectCommand $command): ProjectEntity
    {
        return new ProjectEntity(
            projectName: $command->projectName(),
            label:       $command->label(),
            isComplete:  false
        );
    }

    public function reconstruct(Project $project): ProjectEntity
    {
        $taskIdList = TaskIdList::create($project->tasks->pluck('id'));

        return new ProjectEntity(
            projectId:   $project->id,
            projectName: $project->project_name,
            label:       $project->label,
            isComplete:  $project->is_complete,
            taskIdList:  $taskIdList
        );
    }
}