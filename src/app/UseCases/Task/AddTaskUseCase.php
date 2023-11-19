<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\Domain\ProjectCommand;


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