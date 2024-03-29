<?php declare(strict_types=1);

namespace App\UseCases\Development\Infrastructure;

use App\Models\Development as EqDevelopment;
use App\Models\Project;
use App\Models\Setting;
use App\UseCases\Development\Domain\Development;
use App\UseCases\Development\Domain\FinishedAt;
use App\UseCases\Development\Domain\StartedAt;
use App\UseCases\Development\Domain\Timer;
use App\UseCases\Setting\Domain\SettingEntity;


final readonly class DevelopmentFactory
{
    /**
     * 初期値を生成する
     *
     * @param Project $project
     * 
     * @return Development
     */
    public function create(Project $project): Development
    {        
        /** @var Setting $setting */
        $setting = Setting::get()->first() ?? (new SettingEntity)->create()->toModel();
        
        $defaultTime = $setting->default_time;

        $timer = new Timer(
            defaultTime: $defaultTime,
            remainingTime: $defaultTime
        );

        return new Development(
            projectId: $project->id,
            isStart: false,
            isComplete: false,
            selectedIdList: [],
            timer: $timer
        );
    }

    /**
     * DBからエンティティを再構築する
     * バリデーションはしない
     *
     * @param EqDevelopment $development
     * 
     * @return Development
     */
    public function reconstruct(EqDevelopment $development): Development
    {
        $timer = new Timer(
            defaultTime: $development->default_time,
            remainingTime: $development->remaining_time,
            startedAt: StartedAt::create($development->started_at),
            finishedAt: $development->finished_at ? FinishedAt::create($development->finished_at) : null
        );
        
        return new Development(
            developmentId: $development->id,
            projectId: $development->project_id,
            isStart: $development->is_start,
            isComplete: $development->is_complete,
            selectedIdList: $development->selected_id_list,
            timer: $timer
        );
    }
}
