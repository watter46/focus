<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


final readonly class FetchProjectIncompleteTasksUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(ProjectCommand $command): Project
    {
        try {
            return Project::with('incompleteTasks')
                        ->tasksCount()
                        ->findOrFail($command->projectId());

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}