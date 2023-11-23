<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development;
use App\Models\Project;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\Domain\DevelopmentEntity;


final readonly class RepeatDevelopmentUseCase
{
    public function __construct(private DevelopmentEntity $entity)
    {
        //
    }

    public function execute(DevelopmentCommand $command): Development
    {
        try {
            /** @var Project $project */
            $project = Project::with('latestDevelopment')->findOrFail($command->projectId());

            $development = $this
                            ->entity
                            ->repeat($project->latestDevelopment)
                            ->toModel();
                            
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