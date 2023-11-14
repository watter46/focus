<?php declare(strict_types=1);

namespace App\UseCases\Task\UpdateTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\TaskCommand;


final readonly class UpdateTaskUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(TaskCommand $command): Task
    {
        try {
            /** @var Task $task */
            $task = Task::findOrFail($command->taskId());

            $updated = $task
                        ->toEntity()
                        ->update($command)
                        ->toModel();
            
            DB::transaction(function () use ($updated) {                
                $updated->save();
            });

            return $updated;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
