<?php declare(strict_types=1);

namespace App\UseCases\Task\FetchTask;

use Exception;

use App\Models\Task;


final readonly class FetchTaskUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(string $taskId): Task
    {
        try {
            return Task::findOrFail($taskId);
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}
