<?php declare(strict_types=1);

namespace App\Listeners;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Events\HistoryCreated;
use App\Models\Development as EqDevelopment;
use App\UseCases\History\Infrastructure\HistoryModelBuilder;


class CreateHistory
{
    /**
     * Create the event listener.
     */
    public function __construct(private HistoryModelBuilder $builder)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HistoryCreated $event): void
    {
        try {
            /** @var EqDevelopment $model */
            $model = EqDevelopment::query()
                        ->with([
                            'project:id,project_name,label',
                            'project.tasks' => function ($query) use ($event) {
                                $query
                                    ->select('id', 'project_id', 'name')
                                    ->completed()
                                    ->whereIn('id', $event->development->selectedIdList());
                            }
                        ])
                        ->findOrFail($event->development->developmentId());
            
            $history = $this->builder->toModel($event->development, $model);
            
            $history->save();
            
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('開発情報が見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}