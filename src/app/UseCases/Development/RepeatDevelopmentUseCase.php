<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development;
use App\Models\Project;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\Infrastructure\DevelopmentFactory;
use App\UseCases\Development\Infrastructure\DevelopmentModelBuilder;


final readonly class RepeatDevelopmentUseCase
{
    public function __construct(private DevelopmentFactory $factory, private DevelopmentModelBuilder $builder)
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Project $project */
            $project = Project::with('latestDevelopment')->findOrFail($command->projectId());

            $repeated = $this
                ->factory
                ->create($project)
                ->repeat($project->latestDevelopment);
            
            $development = $this->builder->toModel($repeated);
                            
            DB::transaction(function () use ($development) {
                $development->save();
            });
            
            return $development;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}