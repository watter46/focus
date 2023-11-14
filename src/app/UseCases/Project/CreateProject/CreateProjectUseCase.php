<?php declare(strict_types=1);

namespace App\UseCases\Project\CreateProject;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\ProjectEntity;
use App\UseCases\Project\ProjectCommand;


final readonly class CreateProjectUseCase
{
    public function __construct(private ProjectEntity $entity)
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
            $project = $this
                        ->entity
                        ->create($command)
                        ->toModel();

            DB::transaction(function () use ($project) {        
                $project->save();
                $project->tasks()->save($project->tasks);
            });

            return $project;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}