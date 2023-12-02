<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Development as EqDevelopment;
use App\Models\Task;
use App\UseCases\Development\DevelopmentCommand;


final readonly class FetchRemainingTasksUseCase
{
    public function __construct()
    {
        //
    }
    
    /**
     * 残りのタスクを取得する
     *
     * @param  DevelopmentCommand $command
     * @return Collection<int, Task>
     */
    public function execute(DevelopmentCommand $command): Collection
    {
        try {
            /** @var EqDevelopment $development */
            $development = EqDevelopment::findOrFail($command->developmentId());

            return $development
                        ->load(['project.incompleteTasks' => function ($query) use ($development) {
                            $query->whereNotIn('id', $development->selected_id_list);
                        }])
                        ->project
                        ->incompleteTasks;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}