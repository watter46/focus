<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks;

use Exception;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Support\Str;

use App\Models\Project;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\FetchProjectIncompleteTasksUseCase;
use App\UseCases\Project\FetchProjectTasksUseCase;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\AddTask\AddTaskUseCase;


final class Tasks extends Component
{
    #[Locked]
    public string $projectId;

    #[Locked]
    public Project $project;

    public $refresh;

    #[Rule('required|string|max:20')]
    public $name;

    #[Rule('required|string')]
    public $content;

    public bool $isShowAll = false;

    private readonly AddTaskUseCase $addTask;
    private readonly FetchProjectTasksUseCase $fetchProjectTasks;
    private readonly FetchProjectIncompleteTasksUseCase $fetchProjectIncompleteTasks;

    public function boot(
        AddTaskUseCase $addTask,
        FetchProjectTasksUseCase $fetchProjectTasks,
        FetchProjectIncompleteTasksUseCase $fetchProjectIncompleteTasks
    ) {
        $this->addTask = $addTask;
        $this->fetchProjectTasks = $fetchProjectTasks;
        $this->fetchProjectIncompleteTasks = $fetchProjectIncompleteTasks;
    }

    public function render()
    {
        $this->fetch();

        return view('livewire.project.project-detail.tasks.tasks');
    }

    #[On('fetch-project-incomplete-tasks')]    
    /**
     * プロジェクト内の未完了のタスクIDリストを取得する
     *
     * @return void
     */
    public function fetchProjectIncompleteTaskIdList(): void
    {
        $this->isShowAll = false;
    }

    #[On('fetch-project-tasks')]    
    /**
     * プロジェクト内のタスクIDリストを全て取得する
     *
     * @return void
     */
    public function fetchProjectTasks(): void
    {
        $this->isShowAll = true;
    }
    
    #[On('refetch')]    
    /**
     * タスクIDリストを再度取得する
     *
     * @return void
     */
    public function fetch(): void
    {
        $command = new ProjectCommand($this->projectId);
        
        $this->refresh = (string) Str::ulid();

        $this->project = $this->isShowAll
                ? $this->fetchProjectTasks->execute($command)
                : $this->fetchProjectIncompleteTasks->execute($command);
    }

    #[On('add')]
    /**
     * タスクを追加
     *
     * @return void
     */
    public function add(): void
    {
        $this->validate();
        
        try {
            $command = new ProjectCommand(
                $this->projectId,
                name: $this->name,
                content: $this->content
            );
            
            $this->addTask->execute($command);
            
            $this->notify(Message::createSavedMessage());

            $this->dispatch('refetch');

            $this->reset(['name', 'content']);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}