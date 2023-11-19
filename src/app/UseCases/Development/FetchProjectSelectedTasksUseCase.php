<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development;
use App\Models\Project;
use App\UseCases\Development\Domain\DevelopmentCommand;


final readonly class FetchProjectSelectedTasksUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(DevelopmentCommand $command): Project
    {
        try {
            /** @var Development $development */
            $development = Development::findOrFail($command->developmentId());
            
            $selectedIdList = $development->toEntity()->selectedIdList();

            return Project::with(['tasks' => fn($query) => $query
                                ->findOrFail($selectedIdList)
                            ])
                            ->tasksCount()
                            ->findOrFail($development->project_id);
            
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('モデルが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}