<?php declare(strict_types=1);

namespace App\UseCases\Project;

use App\Constants\ProjectNameConstant;
use Exception;

use App\Models\Project;
use App\Models\Task;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\TaskEntity;


final class ProjectEntity
{
    private string    $projectName;
    private LabelType $label;
    private bool      $isComplete;

    private Project $project;
    private Task    $task;

    public function __construct()
    {
        $this->project = new Project;
    }

    /**
     * 初期値を生成する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function create(ProjectCommand $command): self
    {
        $this->projectName = $command->projectName();
        $this->label       = $command->label();
        $this->isComplete  = false;

        $this->task = (new TaskEntity)->create($command)->toModel();

        $this->validate();
        
        return $this;
    }
    
    /**
     * DBから再構築する
     *
     * @param  Project $project
     * @return self
     */
    public function reconstruct(Project $project): self
    {
        $this->project = $project;

        $this->projectName = $project->project_name;
        $this->label       = $project->label;
        $this->isComplete  = $project->is_complete;
        
        return $this;
    }
    
    /**
     * プロジェクト名を更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateProjectName(ProjectCommand $command): self
    {
        $this->projectName = $command->name();

        $this->validate();
        
        return $this;
    }

    /**
     * ラベルを更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateLabel(ProjectCommand $command): self
    {
        $this->label = $command->label();
        
        return $this;
    }
    
    /**
     * プロジェクトを完了する
     *
     * @return self
     */
    public function complete(): self
    {
        $this->isComplete = true;

        return $this;
    }

    /**
     * プロジェクトを未完了にする
     *
     * @return self
     */
    public function incomplete(): self
    {
        $this->isComplete = false;

        return $this;
    }

    public function addTask(ProjectCommand $command): self
    {
        $task = (new TaskEntity)->create($command)->toModel();
        
        $this->project->tasks->push($task);

        if ($this->project->tasks->count() > 10) {
            throw new Exception("タスクの最大数は10です。");
        }

        return $this;
    }

    public function validate(): void
    {
        ProjectNameConstant::isValid($this->projectName);
    }

    public function toModel(): Project
    {        
        if ($this->project->tasks->isEmpty()) {            
            return $this->project->fromEntity(
                $this->projectName,
                $this->label,
                $this->isComplete
            )->setRelation('tasks', $this->task);
        }

        return $this->project->fromEntity(
            $this->projectName,
            $this->label,
            $this->isComplete
        );
    }
}