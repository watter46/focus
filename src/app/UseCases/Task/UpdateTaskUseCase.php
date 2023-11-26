<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\Infrastructure\TaskFactory;
use App\UseCases\Task\Infrastructure\TaskModelBuilder;
use App\UseCases\Task\TaskCommand;


final readonly class UpdateTaskUseCase
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

            $updated = $this
                ->factory
                ->reconstruct($model)
                ->update($command);

            $task = $this->builder->toModel($updated, $model);
            
            DB::transaction(function () use ($task) {                
                $task->save();
            });

            return $task;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
