<?php declare(strict_types=1);

namespace App\UseCases\Project\Domain;

use Exception;

use App\Constants\ProjectNameConstant;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\UseCases\Project\ProjectCommand;


final readonly class ProjectEntity
{
    private ?string     $projectId;
    private ?string     $projectName;
    private ?LabelType  $label;
    private ?bool       $isComplete;
    private ?TaskIdList $taskIdList;

    public function __construct(
        ?string     $projectId   = null,
        ?string     $projectName = null,
        ?LabelType  $label       = null,
        ?bool       $isComplete  = null,
        ?TaskIdList $taskIdList  = null
    ) {
        $this->projectId   = $projectId;
        $this->projectName = $projectName;
        $this->label       = $label;
        $this->isComplete  = $isComplete;
        $this->taskIdList  = $taskIdList;
    }
    
    /**
     * プロジェクト名を更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateProjectName(ProjectCommand $command): self
    {
        return $this->changeAttribute(projectName: $command->projectName());
    }

    /**
     * ラベルを更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateLabel(ProjectCommand $command): self
    {        
        return $this->changeAttribute(label: $command->label());
    }
    
    /**
     * プロジェクトを完了する
     *
     * @return self
     */
    public function complete(): self
    {
        return $this->changeAttribute(isComplete: true);
    }

    /**
     * プロジェクトを未完了にする
     *
     * @return self
     */
    public function incomplete(): self
    {
        return $this->changeAttribute(isComplete: false);
    }
        
    /**
     * タスクを追加できるか判定する
     *
     * @return bool
     */
    public function canAddTask(): bool
    {
        if (!$this->taskIdList->canAddTask()) {
            throw new Exception(TaskIdList::TASK_LIMIT_EXCEEDED_MESSAGE);
        };
        
        return true;
    }

    public function validate(): void
    {
        ProjectNameConstant::isValid($this->projectName);
    }

    private function changeAttribute(
        ?string    $projectId   = null,
        ?string    $projectName = null,
        ?LabelType $label       = null,
        ?bool      $isComplete  = null): self
    {
        $this->validate();
        
        return new self(
            projectId:   $projectId   ?? $this->projectId,
            projectName: $projectName ?? $this->projectName,
            label:       $label       ?? $this->label,
            isComplete:  $isComplete  ?? $this->isComplete
        );
    }

    public function projectId(): ?string
    {
        return $this->projectId;
    }

    public function projectName(): string
    {
        return $this->projectName;
    }

    public function label(): LabelType
    {
        return $this->label;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }
}