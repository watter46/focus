<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\Infrastructure\ProjectFactory;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\Infrastructure\TaskFactory;
use App\UseCases\Task\Infrastructure\TaskModelBuilder;


final readonly class AddTaskUseCase
{
    public function __construct(
        private ProjectFactory $factory,
        private TaskFactory    $taskFactory,
        private TaskModelBuilder $builder
    ) {
        //
    }

    public function execute(ProjectCommand $command): void
    {
        try {
            /** @var Project $model */
            $model = Project::query()
                        ->with('tasks')
                        ->findOrFail($command->projectId());
                        
            $project = $this->factory->reconstruct($model);

            if (!$project->canAddTask()) return;
            
            $created = $this->taskFactory->create($command);

            $task = $this->builder->toModel($created);
                        
            DB::transaction(function () use ($model, $task) {                                
                $task->project()->associate($model);
                
                $task->save();
            });

        } catch (Exception $e) {
            throw $e;
        }
    }
}