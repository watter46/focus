<?php declare(strict_types=1);

namespace App\UseCases\Development;

use DB;
use Exception;

use App\Models\Development;
use App\Models\Project;

use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\Domain\DevelopmentEntity;


final readonly class StartDevelopmentUseCase
{
    public function __construct(private DevelopmentEntity $entity)
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            $project = Project::findOrFail($command->projectId());
            
            $development = $this
                        ->entity
                        ->create($project)
                        ->start($command)
                        ->toModel();

            DB::transaction(function () use ($development) {
                $development->save();
            });

            return $development;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}