<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;

use App\Models\Project;
use App\Models\Development;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\Domain\DevelopmentEntity;


final readonly class FetchDevelopmentUseCase
{
    public function __construct(private DevelopmentEntity $entity)
    {
        //
    }
    
    /**
     * Developmentを取得する
     *
     * @param  DevelopmentCommand $command
     * @return Development
     */
    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Project $project */
            $project = Project::with('latestDevelopment')->findOrFail($command->projectId());

            if ($project->canDevelop()) {
                return $project->latestDevelopment;
            }
            
            return $this->entity->create($project)->toModel();

        } catch (Exception $e) {
            throw $e;
        }
    }
}