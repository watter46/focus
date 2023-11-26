<?php declare(strict_types=1);

namespace App\UseCases\Project\Infrastructure;

use Auth;
use App\Models\Project;
use App\UseCases\Project\Domain\ProjectEntity;


final readonly class ProjectModelBuilder
{
    public function toModel(ProjectEntity $project, Project $model = new Project): Project
    {
        $model->id           = $project->projectId();
        $model->user_id      = Auth::user()->id;
        $model->project_name = $project->projectName();
        $model->label        = $project->label();
        $model->is_complete  = $project->isComplete();

        return $model;
    }
}