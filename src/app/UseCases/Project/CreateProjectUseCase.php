<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\Infrastructure\ProjectFactory;
use App\UseCases\Project\Infrastructure\ProjectModelBuilder;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\Infrastructure\TaskFactory;
use App\UseCases\Task\Infrastructure\TaskModelBuilder;


final readonly class CreateProjectUseCase
{
    public function __construct(
        private ProjectFactory $factory,
        private ProjectModelBuilder $builder,
        private TaskFactory $taskFactory,
        private TaskModelBuilder $taskBuilder)
    {
        //
    }
    
    /**
     * プロジェクトとタスクを作成する
     *
     * @param ProjectCommand $command
     * @return Project
     */
    public function execute(ProjectCommand $command): Project
    {
        try {
            $created = $this
                ->factory
                ->create($command);

            $taskCreated = $this
                ->taskFactory
                ->create($command);

            $project = $this->builder->toModel($created);
            $task    = $this->taskBuilder->toModel($taskCreated);

            DB::transaction(function () use ($project, $task) {        
                $project->save();
                
                $task->project()->associate($project);

                $task->save();
            });

            return $project;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}