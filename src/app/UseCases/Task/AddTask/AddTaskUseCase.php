<?php declare(strict_types=1);

namespace App\UseCases\Task\AddTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


final readonly class AddTaskUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(ProjectCommand $command): void
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());

            $added = $project
                        ->toEntity()
                        ->addTask($command)
                        ->toModel();
                        
            DB::transaction(function () use ($added) {                                
                $added->tasks()->save($added->tasks->last());
            });

        } catch (Exception $e) {
            throw $e;
        }
    }
}