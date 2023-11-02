<?php declare(strict_types=1);

namespace App\UseCases\Project\FetchProjectTaskIdList;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


final readonly class FetchProjectTaskIdListUseCase
{
    public function execute(ProjectCommand $command): Project
    {
        try {
            $project = Project::query()
                            ->tasksCount()
                            ->findOrFail($command->projectId());

            $project->tasks = $project->tasks->pluck('id');
            
            return $project;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
