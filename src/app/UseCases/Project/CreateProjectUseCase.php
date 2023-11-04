<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\Task;


final readonly class CreateProjectUseCase
{
    public function __construct()
    {
        //
    }
    
    /**
     * プロジェクトとタスクを作成する
     *
     * @param CreateProjectCommand $command
     * @return Project
     */
    public function execute(CreateProjectCommand $command): Project
    {
        try {
            $project = (new Project)->createProject($command);
            $task    = (new Task)->createTask($command->taskInProjectCommand());

            DB::transaction(function () use ($project, $task) {        
                $project->save();
                $project->tasks()->save($task);
            });

            return $project;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}