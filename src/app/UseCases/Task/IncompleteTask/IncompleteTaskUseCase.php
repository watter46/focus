<?php declare(strict_types=1);

namespace App\UseCases\Task\IncompleteTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\TaskCommand;


final readonly class IncompleteTaskUseCase
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

            $incompleted = $task
                            ->toEntity()
                            ->incomplete()
                            ->toModel();
            
            DB::transaction(function () use ($incompleted) {
                $incompleted->save();
            });

            return $incompleted;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
