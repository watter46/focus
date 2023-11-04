<?php declare(strict_types=1);

namespace App\UseCases\Task\CompleteTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Task;


final readonly class CompleteTaskUseCase
{
    public function __construct()
    {
        //       
    }

    public function execute(Task $task): void
    {
        try {
            DB::transaction(function () use ($task) {
                $task->complete();

                $task->save();
            });

        } catch (Exception $e) {
            throw $e;
        }
    }
}
