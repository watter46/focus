<?php declare(strict_types=1);

namespace App\UseCases\Project\RegisterTask;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\Task;
use App\UseCases\Project\RegisterTask\TaskInProject;


final readonly class RegisterTaskUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(TaskInProject $validator): void
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($validator->projectId());

            if ($validator->exceedsTaskLimit($project)) {
                throw new Exception("タスクの最大数は10です。");
            }

            $task = (new Task)->createTask($validator);
            
            DB::transaction(function () use ($project, $task) {                                
                $project->tasks()->save($task);
            });

        } catch (Exception $e) {
            throw $e;
        }
    }
}