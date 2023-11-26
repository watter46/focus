<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks;

use Exception;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Project;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\FetchProjectIncompleteTasksUseCase;
use App\UseCases\Project\FetchProjectTasksUseCase;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\AddTaskUseCase;


final class Tasks extends Component
{
    #[Locked]
    public string $projectId;

    #[Locked]
    public Project $project;

    #[Locked]
    /** @var Collection<int, Task> $tasks */
    public Collection $tasks;

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
     * プロジェクト内の未完了のタスクを取得する
     *
     * @return void
     */
    public function fetchProjectIncompleteTaskIdList(): void
    {
        $this->isShowAll = false;
    }

    #[On('fetch-project-tasks')]    
    /**
     * プロジェクト内のタスクを全て取得する
     *
     * @return void
     */
    public function fetchProjectTasks(): void
    {
        $this->isShowAll = true;
    }
    
    #[On('refetch')]    
    /**
     * タスクを再度取得する
     *
     * @return void
     */
    public function fetch(): void
    {
        $command = ProjectCommand::find($this->projectId);
        
        $this->refresh = (string) Str::ulid();

        $this->project = $this->isShowAll
                ? $this->fetchProjectTasks->execute($command)
                : $this->fetchProjectIncompleteTasks->execute($command);

        $this->tasks = $this->isShowAll
                ? $this->project->tasks
                : $this->project->incompleteTasks;
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
            $command = ProjectCommand::addTask(
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