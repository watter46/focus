<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Carbon\Carbon;

use App\Models\Development;
use App\Models\Project;
use App\Models\Setting;
use App\UseCases\Setting\SettingEntity;


final class DevelopmentEntity
{
    private bool    $isStart;
    private bool    $isComplete;
    private int     $defaultTime;
    private int     $remainingTime;
    private ?Carbon $startedAt;
    private ?Carbon $finishedAt;
    private array   $selectedIdList;
    
    private Project     $project;
    private Development $development;

    public function __construct()
    {
        $this->development = new Development;
    }
        
    /**
     * 初期値を生成する
     *
     * @param  Project $project
     * @return self
     */
    public function create(Project $project): self
    {
        $this->project = $project;
        
        /** @var Setting $setting */
        $setting = Setting::get()->first() ?? (new SettingEntity)->create()->toModel();;
        
        $defaultTime = $setting->default_time;
        
        $this->isStart        = false;
        $this->isComplete     = false;
        $this->defaultTime    = $defaultTime;
        $this->remainingTime  = $defaultTime;
        $this->startedAt      = null;
        $this->finishedAt     = null;
        $this->selectedIdList = [];

        $this->validate();

        return $this;
    }
    
    /**
     * DBからエンティティを再構築する
     *
     * @param  Development $development
     * @return self
     */
    public function reconstruct(Development $development): self
    {
        $this->development = $development;

        $this->isStart        = $development->is_start;
        $this->isComplete     = $development->is_complete;
        $this->defaultTime    = $development->default_time;
        $this->remainingTime  = $development->remaining_time;
        $this->startedAt      = $development->started_at;
        $this->finishedAt     = $development->finished_at;
        $this->selectedIdList = $development->selected_id_list;

        return $this;
    }
    
    /**
     * 開発を開始する
     *
     * @param  DevelopmentCommand $command
     * @return self
     */
    public function start(DevelopmentCommand $command): self
    {
        $this->isStart        = true;
        $this->defaultTime    = $command->defaultTime();
        $this->remainingTime  = $command->remainingTime();
        $this->startedAt      = now();
        $this->selectedIdList = $command->selectedIdList();

        $this->validate();
        
        return $this;
    }
    
    /**
     * 開発をとめる
     *
     * @param  DevelopmentCommand $command
     * @return self
     */
    public function stop(DevelopmentCommand $command): self
    {
        $this->remainingTime = $command->remainingTime();

        $this->validate();
        
        return $this;
    }
    
    /**
     * 開発を終了する
     *
     * @return self
     */
    public function finish(): self
    {
        $this->isComplete = true;
        $this->finishedAt = now();

        $this->validate();
        
        return $this;
    }
    
    /**
     * 同じプロジェクトを再開発する
     *
     * @return self
     */
    public function repeat(Development $development): self
    {
        $this->project = $development->project;
        
        $this->isStart        = true;
        $this->isComplete     = false;
        $this->defaultTime    = $development->default_time;
        $this->remainingTime  = $development->default_time;
        $this->startedAt      = now();
        $this->finishedAt     = null;
        $this->selectedIdList = $development->selected_id_list;

        $this->validate();

        return $this;
    }
    
    /**
     * タスクを変更する
     *
     * @return void
     */
    public function changeTask(DevelopmentCommand $command): self
    {
        $this->selectedIdList = collect($this->selectedIdList)
                                ->merge($command->selectedIdList())
                                ->toArray();

        return $this;
    }

    public function validate(): void
    {
        // validateを書く
    }
    
    /**
     * 開発中のタスクIDを取得する
     *
     * @return array
     */
    public function selectedIdList(): array
    {
        return $this->selectedIdList;
    }
    
    /**
     * モデルに変換する
     *
     * @return Development
     */
    public function toModel(): Development
    {
        if (!$this->development->project) {
            return $this->development->fromEntity(
                $this->isStart,
                $this->isComplete,
                $this->defaultTime,
                $this->remainingTime,
                $this->startedAt,
                $this->finishedAt,
                $this->selectedIdList
            )
            ->project()
            ->associate($this->project);
        }

        return $this->development->fromEntity(
            $this->isStart,
            $this->isComplete,
            $this->defaultTime,
            $this->remainingTime,
            $this->startedAt,
            $this->finishedAt,
            $this->selectedIdList
        );
    }
}