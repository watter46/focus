<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\Domain\ProjectCommand;


final readonly class FetchProjectUseCase
{    
    /**
     * 指定のプロジェクトを取得する
     *
     * @param  ProjectCommand $command
     * @return Project
     */
    public function execute(ProjectCommand $command): Project
    {
        try {
            return Project::query()
                        ->tasksCount()
                        ->findOrFail($command->projectId());

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}