<?php declare(strict_types=1);

namespace App\UseCases\Development;

use DB;
use Exception;

use App\Models\Development;
use App\Models\Project;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


final readonly class StartDevelopmentUseCase
{
    public function __construct(private DevelopmentFactory $factory, private DevelopmentModelBuilder $builder)
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());
            
            $started = $this
                ->factory
                ->create($project)
                ->start($command);

            $development = $this->builder->toModel($started);

            DB::transaction(function () use ($development) {
                $development->save();
            });

            return $development;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}