<?php declare(strict_types=1);

namespace App\UseCases\Project\CompleteProject;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


final readonly class CompleteProjectUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());

            $completed = $project
                            ->toEntity()
                            ->complete()
                            ->toModel();
                            
            DB::transaction(function () use ($completed) {
                $completed->save();
            });

            return $completed;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}