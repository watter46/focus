<?php declare(strict_types=1);

namespace App\UseCases\Development\Infrastructure;

use App\Models\Development;
use App\UseCases\Development\Domain\DevelopmentEntity;


final readonly class DevelopmentModelBuilder
{    
    public function __construct()
    {
        //
    }

    public function toModel(DevelopmentEntity $dev, Development $model = new Development): Development
    {
        $model->id               = $dev->developmentId();
        $model->project_id       = $dev->projectId();
        $model->is_start         = $dev->isStart();
        $model->is_complete      = $dev->isComplete();
        $model->default_time     = $dev->timer()->defaultTime();
        $model->remaining_time   = $dev->timer()->remainingTime();
        $model->started_at       = $dev->timer()->startedAt()?->value();
        $model->finished_at      = $dev->timer()->finishedAt()?->value();
        $model->selected_id_list = $dev->selectedIdList();

        return $model;
    }
}