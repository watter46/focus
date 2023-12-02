<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development as EqDevelopment;
use App\Models\Project;
use App\UseCases\Development\DevelopmentCommand;


final readonly class FetchProjectSelectedTasksUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(DevelopmentCommand $command): Project
    {
        try {
            /** @var EqDevelopment $development */
            $development = EqDevelopment::findOrFail($command->developmentId());
                        
            $selectedIdList = $development->selected_id_list;
            
            return Project::query()
                        ->with(['tasks' => fn($query) => $query
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