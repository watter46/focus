<?php declare(strict_types=1);

namespace App\UseCases\Task\Infrastructure;

use App\Models\Task as EqTask;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\Domain\Task;


final readonly class TaskFactory
{
    public function create(ProjectCommand $command): Task
    {
        return new Task(
            name:       $command->name(),
            content:    $command->content(),
            isComplete: false
        );
    }

    public function reconstruct(EqTask $task): Task
    {
        return new Task(
            taskId:     $task->id,
            projectId:  $task->project_id,
            name:       $task->name,
            content:    $task->content,
            isComplete: $task->is_complete
        );
    }
}