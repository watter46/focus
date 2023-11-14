<?php declare(strict_types=1);

namespace App\UseCases\Project\UpdateProjectName;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Project\ProjectEntity;

final readonly class UpdateProjectNameUseCase
{
    public function __construct(private ProjectEntity $entity)
    {
        //
    }
    
    /**
     * プロジェクト名を更新する
     *
     * @param  ProjectCommand $command
     * @return Project
     */
    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());

            $updated = $project
                        ->toEntity()
                        ->updateProjectName($command)
                        ->toModel();
                        
            DB::transaction(function () use ($updated) {
                $updated->save();
            });

            return $updated;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}