<?php declare(strict_types=1);

namespace App\UseCases\Task\Domain;

use App\Constants\TaskContentConstants;
use App\Constants\TaskNameConstants;
use App\UseCases\Task\TaskCommand;


final readonly class Task
{
    private ?string $taskId;
    private ?string $projectId;
    private ?string $name;
    private ?string $content;
    private ?bool   $isComplete;
        
    public function __construct(
        ?string $taskId     = null,
        ?string $projectId  = null,
        ?string $name       = null,
        ?string $content    = null,
        ?bool   $isComplete = null
    ) {
        $this->taskId     = $taskId;
        $this->projectId  = $projectId;
        $this->name       = $name;
        $this->content    = $content;
        $this->isComplete = $isComplete;
    }
    
    /**
     * タスクを完了する
     *
     * @return self
     */
    public function complete(): self
    {
        return $this->changeAttribute(isComplete: true);
    }
    
    /**
     * タスクを未完了にする
     *
     * @return self
     */
    public function incomplete(): self
    {
        return $this->changeAttribute(isComplete: false);
    }
    
    /**
     * タスクをアップデートする
     *
     * @param  TaskCommand $command
     * @return self
     */
    public function update(TaskCommand $command): self
    {
        return $this->changeAttribute(
            name:    $command->name(),
            content: $command->content()
        );
    }
    
    /**
     * プロパティをバリデーションする
     *
     * @return void
     */
    public function validate()
    {
        TaskNameConstants::isValid($this->name);
        TaskContentConstants::isValid($this->content);
    }
    
    /**
     * プロパティを変更する
     *
     * @return self
     */
    private function changeAttribute(
        ?string $taskId     = null,
        ?string $projectId  = null,
        ?string $name       = null,
        ?string $content    = null,
        ?bool   $isComplete = null): self
    {
        $this->validate();

        return new self(
            taskId:     $taskId     ?? $this->taskId,
            projectId:  $projectId  ?? $this->projectId,
            name:       $name       ?? $this->name,
            content:    $content    ?? $this->content,
            isComplete: $isComplete ?? $this->isComplete
        );
    }

    public function taskId(): ?string
    {
        return $this->taskId;
    }

    public function projectId(): string
    {
        return $this->projectId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }
}