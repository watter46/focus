<?php declare(strict_types=1);

namespace App\UseCases\Task;

use App\Constants\TaskContentConstants;
use App\Constants\TaskNameConstants;
use App\Models\Task;
use App\UseCases\Project\ProjectCommand;


final class TaskEntity
{
    private string $name;
    private string $content;
    private bool   $isComplete;
    
    private Task $task;
    
    public function __construct()
    {
        $this->task = new Task;
    }
    
    public function create(ProjectCommand $command): self
    {
        $this->name       = $command->name();
        $this->content    = $command->content();
        $this->isComplete = false;

        $this->validate();
        
        return $this;
    }

    public function reconstruct(Task $task): self
    {
        $this->task = $task;
        
        $this->name       = $task->name;
        $this->content    = $task->content;
        $this->isComplete = $task->is_complete;
        
        return $this;
    }

    public function complete(): self
    {
        $this->isComplete = true;
        
        return $this;
    }

    public function incomplete(): self
    {
        $this->isComplete = false;
        
        return $this;
    }

    public function update(TaskCommand $command): self
    {
        $this->name    = $command->name();
        $this->content = $command->content();

        $this->validate();
        
        return $this;
    }

    public function validate()
    {
        TaskNameConstants::isValid($this->name);
        TaskContentConstants::isValid($this->content);
    }
    
    public function toModel(): Task
    {
        return $this->task->fromEntity(
            $this->name,
            $this->content,
            $this->isComplete
        );
    }
}