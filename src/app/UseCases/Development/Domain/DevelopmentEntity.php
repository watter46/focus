<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use App\Models\Development;
use App\UseCases\Util\UlidValidator;
use App\UseCases\Development\DevelopmentCommand;


final readonly class DevelopmentEntity
{        
    private ?string $developmentId;
    private ?string $projectId;
    private ?bool   $isStart;
    private ?bool   $isComplete;
    private ?array  $selectedIdList;
    private Timer   $timer;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ?string $developmentId  = null,
        ?string $projectId      = null,
        ?bool   $isStart        = null,
        ?bool   $isComplete     = null,
        ?array  $selectedIdList = null,
        ?Timer  $timer          = null
    ) {
        $this->developmentId  = $developmentId;
        $this->projectId      = $projectId;
        $this->isStart        = $isStart;
        $this->isComplete     = $isComplete;
        $this->selectedIdList = $selectedIdList;
        $this->timer          = $timer ?? new Timer;
    }
    
    /**
     * 開発を開始する
     *
     * @param DevelopmentCommand $command
     * 
     * @return self
     */
    public function start(DevelopmentCommand $command): self
    {
        return $this->changeAttribute(
            projectId: $command->projectId(),
            isStart: true,
            selectedIdList: $command->selectedIdList(),
            timer: $this->timer->start(defaultTime: $command->defaultTime())
        );
    }
    
    /**
     * 開発をとめる
     *
     * @param DevelopmentCommand $command
     * 
     * @return self
     */
    public function stop(DevelopmentCommand $command): self
    {
        return $this->changeAttribute(
            timer: $this->timer->stop($command->remainingTime())
        );
    }
    
    /**
     * 開発を完了する
     *
     * @return self
     */
    public function complete(): self
    {        
        return $this->changeAttribute(
            isComplete: true,
            timer: $this->timer->complete()
        );
    }
    
    /**
     * 開発を途中でやめる
     *
     * @return self
     */
    public function clear(): self
    {        
        return $this->changeAttribute(
            isComplete: true,
            timer: $this->timer->clear()
        );
    }
    
    /**
     * 同じプロジェクトを再開発する
     * 
     * @param Development $development
     * 
     * @return self
     */
    public function repeat(Development $development): self
    {        
        return $this->changeAttribute(
            projectId: $development->project_id,
            isStart: true,
            isComplete: false,
            selectedIdList: $development->selected_id_list,
            timer: $this->timer->start(defaultTime: $development->default_time)
        );
    }
    
    /**
     * タスクを変更する
     * 
     * @param DevelopmentCommand $command
     * 
     * @return self
     */
    public function changeTask(DevelopmentCommand $command): self
    {
        $taskIdList = collect($this->selectedIdList)
                ->merge($command->selectedIdList())
                ->toArray();
        
        return $this->changeAttribute(selectedIdList: $taskIdList);
    }

    private function validate(): void
    {
        if ($this->developmentId) {
            UlidValidator::isValid($this->developmentId);
        }

        UlidValidator::isValid($this->projectId);
    }
        
    /**
     * プロパティを変更する
     * 
     * @param ?string $developmentId,
     * @param ?string $projectId,
     * @param ?bool   $isStart,
     * @param ?bool   $isComplete,
     * @param ?array  $selectedIdList,
     * @param ?Timer  $timer
     * 
     * @return self
     */
    private function changeAttribute(
        ?string $developmentId = null,
        ?string $projectId = null,
        ?bool   $isStart = null,
        ?bool   $isComplete = null,
        ?array  $selectedIdList = null,
        ?Timer  $timer = null): self
    {
        $this->validate();
        
        return new self(
            developmentId:  $developmentId  ?? $this->developmentId,
            projectId:      $projectId      ?? $this->projectId,
            isStart:        $isStart        ?? $this->isStart,
            isComplete:     $isComplete     ?? $this->isComplete,
            selectedIdList: $selectedIdList ?? $this->selectedIdList,
            timer:          $timer          ?? $this->timer
        );
    }

    public function developmentId(): ?string
    {
        return $this->developmentId;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function isStart(): bool
    {
        return $this->isStart;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    public function selectedIdList(): array
    {
        return $this->selectedIdList;
    }

    public function timer(): Timer
    {
        return $this->timer;
    }
}