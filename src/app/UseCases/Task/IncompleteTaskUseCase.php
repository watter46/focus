<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\Infrastructure\TaskFactory;
use App\UseCases\Task\Infrastructure\TaskModelBuilder;
use App\UseCases\Task\TaskCommand;


final readonly class IncompleteTaskUseCase
{
    public function __construct(private TaskFactory $factory, private TaskModelBuilder $builder)
    {
        //
    }

    public function execute(TaskCommand $command): Task
    {
        try {
            /** @var Task $model */
            $model = Task::findOrFail($command->taskId());
            
            $incompleted = $this
                ->factory
                ->reconstruct($model)
                ->incomplete();

            $task = $this->builder->toModel($incompleted, $model);
            
            DB::transaction(function () use ($task) {
                $task->save();
            });

            return $task;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
