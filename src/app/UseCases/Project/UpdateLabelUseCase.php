<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\Infrastructure\ProjectFactory;
use App\UseCases\Project\Infrastructure\ProjectModelBuilder;
use App\UseCases\Project\ProjectCommand;


final readonly class UpdateLabelUseCase
{
    public function __construct(private ProjectFactory $factory, private ProjectModelBuilder $builder)
    {
        //
    }
    
    /**
     * ラベルをアップデートする
     *
     * @param  ProjectCommand $command
     * @return Project
     */
    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $model */
            $model = Project::findOrFail($command->projectId());
            
            $updated = $this
                ->factory
                ->reconstruct($model)
                ->updateLabel($command);

            $project = $this->builder->toModel($updated, $model);
            
            DB::transaction(function () use ($project) {                
                $project->save();
            });

            return $project;

        } catch (Exception $e) {
            throw $e;
        }
    }
}