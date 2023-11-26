<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\Infrastructure\ProjectFactory;
use App\UseCases\Project\Infrastructure\ProjectModelBuilder;
use App\UseCases\Project\ProjectCommand;


final readonly class IncompleteProjectUseCase
{
    public function __construct(private ProjectFactory $factory, private ProjectModelBuilder $builder)
    {
        //
    }

    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $model */
            $model = Project::findOrFail($command->projectId());

            $incompleted = $this
                ->factory
                ->reconstruct($model)
                ->incomplete();

            $project = $this->builder->toModel($incompleted, $model);
                        
            DB::transaction(function () use ($project) {
                $project->save();
            });

            return $project;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {

        }
    }
}