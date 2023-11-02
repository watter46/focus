<?php declare(strict_types=1);

namespace App\UseCases\Task\UpdateTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\UpdateTask\UpdateTaskCommand;


final readonly class UpdateTaskUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(UpdateTaskCommand $command): void
    {
        try {
            /** @var Task $task */
            $task = Task::findOrFail($command->taskId());

            $task->updateTask($command);
            
            DB::transaction(function () use ($task) {                
                $task->save();
            });
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
