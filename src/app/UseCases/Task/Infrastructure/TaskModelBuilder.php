<?php declare(strict_types=1);

namespace App\UseCases\Task\Infrastructure;

use App\Models\Task as EqTask;
use App\UseCases\Task\Domain\Task;


final readonly class TaskModelBuilder
{
    public function toModel(Task $task, EqTask $model = new EqTask): EqTask
    {
        $model->id          = $task->taskId();
        $model->name        = $task->name();
        $model->content     = $task->content();
        $model->is_complete = $task->isComplete();
        
        return $model;
    }
}