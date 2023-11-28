<?php declare(strict_types=1);

namespace App\UseCases\History\Infrastructure;

use Exception;
use Illuminate\Support\Facades\Auth;

use App\Models\Development as EqDevelopment;
use App\Models\History as EqHistory;
use App\UseCases\Development\Domain\DevelopmentEntity;
use App\UseCases\History\Domain\ElapsedTime;


final readonly class HistoryModelBuilder
{
    public function __construct()
    {
        
    }
    
    public function toModel(DevelopmentEntity $development, EqDevelopment $model): EqHistory
    {
        try {
            $history = new EqHistory;

            $history->user_id      = Auth::user()->id;
            $history->project_name = $model->project->project_name;
            $history->label        = $model->project->label;
            $history->started_at   = $model->started_at;
            $history->finished_at  = $model->finished_at;            
            $history->elapsed_time = ElapsedTime::create($development)->value();
            $history->completed_task_list = $model->project->tasks->pluck('name')->toArray();
            
            return $history;

        } catch (Exception $e) {
            throw $e;
        }
    }
}