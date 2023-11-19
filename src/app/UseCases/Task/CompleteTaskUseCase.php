<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\UseCases\Task\Domain\TaskCommand;


final readonly class CompleteTaskUseCase
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

            $completed = $task
                        ->toEntity()
                        ->complete()
                        ->toModel();
                        
            DB::transaction(function () use ($completed) {
                $completed->save();
            });

            return $completed;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
