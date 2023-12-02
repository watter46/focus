<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;

use App\Models\Project;
use App\Models\Development as EqDevelopment;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


final readonly class FetchDevelopmentUseCase
{
    public function __construct(private DevelopmentFactory $factory, private DevelopmentModelBuilder $builder)
    {
        //
    }
    
    /**
     * Developmentを取得する
     *
     * @param  DevelopmentCommand $command
     * @return EqDevelopment
     */
    public function execute(DevelopmentCommand $command): EqDevelopment
    {
        try {
            /** @var Project $project */
            $project = Project::with('latestDevelopment')->findOrFail($command->projectId());

            if ($project->canDevelop()) {
                return $project->latestDevelopment;
            }

            $development = $this->factory->create($project);
            
            return $this->builder->toModel($development);

        } catch (Exception $e) {
            throw $e;
        }
    }
}