<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;

use App\Models\Task;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Task\CompleteTask\CompleteTaskUseCase;
use App\UseCases\Task\FetchTask\FetchTaskUseCase;
use App\UseCases\Task\IncompleteTask\IncompleteTaskUseCase;
use App\UseCases\Task\TaskCommand;
use App\UseCases\Task\UpdateTask\UpdateTaskCommand;
use App\UseCases\Task\UpdateTask\UpdateTaskUseCase;


class TaskDetail extends Component
{
    #[Locked]
    public Task $task;

    #[Locked]
    public string $taskId;

    public bool $isComplete;
    public bool $isEdit = false;

    #[Rule('required|string|max:20')]
    public $name;

    #[Rule('required|string')]
    public $content;

    private readonly CompleteTaskUseCase   $completeTask;
    private readonly IncompleteTaskUseCase $incompleteTask;
    private readonly UpdateTaskUseCase     $updateTask;
    private readonly FetchTaskUseCase      $fetchTask;

    public function boot(
        CompleteTaskUseCase   $completeTask,
        InCompleteTaskUseCase $incompleteTask,
        UpdateTaskUseCase     $updateTask,
        FetchTaskUseCase      $fetchTask
    ) {
        $this->completeTask   = $completeTask;
        $this->incompleteTask = $incompleteTask;
        $this->updateTask     = $updateTask;
        $this->fetchTask      = $fetchTask;
    }

    public function mount()
    {
        $this->fetchTask();
    }

    public function render()
    {
        return view('livewire.project.project-detail.tasks.task-detail.task-detail');
    }

    private function fetchTask(): void
    {
        $this->task = $this->fetchTask->execute($this->taskId);

        $this->isComplete = $this->task->is_complete;
        $this->name       = $this->task->getRawOriginal('name');
        $this->content    = $this->task->getRawOriginal('content');
    }

    /**
     * タスクを完了状態にする
     *
     * @return void
     */
    public function complete(): void
    {
        try {
            $command = new TaskCommand($this->taskId);
            
            $this->completeTask->execute($command);

            $this->dispatch('refetch');
            
            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * タスクを未完了状態にする
     *
     * @return void
     */
    public function incomplete(): void
    {
        try {
            $command = new TaskCommand($this->taskId);

            $this->incompleteTask->execute($command);

            $this->dispatch('refetch');
                
            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * タスクをアップデート
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();

        try {
            $command = new TaskCommand(
                $this->taskId,
                name: $this->name,
                content: $this->content
            );
            
            $this->updateTask->execute($command);

            $this->dispatch('refetch');

            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    #[On('updateCheckbox')]
    /*
     * Checkboxを更新する
     * 
     * @param string $content
     * @return void
     */
    public function updateCheckbox(string $content): void
    {
        try {
            $command = new TaskCommand(
                $this->taskId,
                name: $this->name,
                content: $content
            );
    
            $this->updateTask->execute($command);
                
            $this->dispatch('refetch');
            
            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    #[On('reorder')]
    /**
     * 並び替える
     *
     * @param string $content
     * @return void
     */
    public function reorder(string $content): void
    {
        try {
            $command = new TaskCommand(
                $this->taskId,
                name: $this->name,
                content: $content
            );
    
            $this->updateTask->execute($command);
                
            $this->dispatch('refetch');
            
            $this->notify(Message::createSavedMessage());
            
        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}