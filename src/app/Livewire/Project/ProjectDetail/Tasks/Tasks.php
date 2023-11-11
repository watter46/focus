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
use App\UseCases\Project\FetchProjectTaskIdList\FetchProjectTaskIdListUseCase;
use App\UseCases\Project\FetchProjectIncompleteTaskIdList\FetchProjectIncompleteTaskIdListUseCase;
use App\UseCases\Project\ProjectCommand;
use App\UseCases\Task\RegisterTask\RegisterTaskUseCase;
use App\UseCases\Task\RegisterTask\TaskInProject;


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

    private readonly RegisterTaskUseCase $registerTask;
    private readonly FetchProjectTaskIdListUseCase $fetchProjectTaskIdList;
    private readonly FetchProjectIncompleteTaskIdListUseCase $fetchProjectIncompleteTaskIdList;

    public function boot(
        FetchProjectTaskIdListUseCase $fetchProjectTaskIdList,
        RegisterTaskUseCase $registerTask,
        FetchProjectIncompleteTaskIdListUseCase $fetchProjectIncompleteTaskIdList,
    ) {
        $this->fetchProjectTaskIdList = $fetchProjectTaskIdList;
        $this->fetchProjectIncompleteTaskIdList = $fetchProjectIncompleteTaskIdList;
        $this->registerTask = $registerTask;
    }

    public function render()
    {
        $this->fetchIdList();

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
    public function fetchIdList(): void
    {
        $command = new ProjectCommand($this->projectId);
        
        $this->refresh = (string) Str::ulid();

        $this->project = $this->isShowAll
                ? $this->fetchProjectTaskIdList->execute($command)
                : $this->fetchProjectIncompleteTaskIdList->execute($command);
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
            $validator = new TaskInProject(
                name: $this->name,
                content: $this->content,
                projectId: $this->projectId
            );
            
            $this->registerTask->execute($validator);
            
            $this->notify(Message::createSavedMessage());

            $this->dispatch('refetch');

            $this->reset(['name', 'content']);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}