<?php declare(strict_types=1);

namespace App\Livewire\Development\TaskSelector;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Locked;

use App\Models\Project;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\FetchProjectSelectedTasksUseCase;
use App\UseCases\Project\Domain\ProjectCommand;
use App\UseCases\Task\AddTaskUseCase;


final class InprogressTasks extends Component
{
    #[Locked]
    public string $projectId;

    #[Locked]
    public string $developmentId;

    #[Locked]
    public Project $project;

    public Collection $selected;
    public string $refreshId;

    #[Rule('required|string|max:20')]
    public $name;

    #[Rule('required|string')]
    public $content;

    private readonly FetchProjectSelectedTasksUseCase $fetchProjectSelectedTasks;
    private readonly AddTaskUseCase $addTask;

    public function boot(
        FetchProjectSelectedTasksUseCase $fetchProjectSelectedTasks,
        AddTaskUseCase $addTask
    ) {
        $this->fetchProjectSelectedTasks = $fetchProjectSelectedTasks;
        $this->addTask = $addTask;
    }

    public function mount()
    {
        $this->setupView($this->developmentId);
    }

    public function render()
    {
        return view('livewire.development.task-selector.inprogress-tasks');
    }
        
    /**
     * setupView
     *
     * @return void
     */
    #[On('refetch')]
    public function setupView(): void
    {
        try {
            $command = new DevelopmentCommand(developmentId: $this->developmentId);
        
            $this->project   = $this->fetchProjectSelectedTasks->execute($command);
            $this->refreshId = (string) Str::ulid();

        } catch (Exception $e) {            
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * タスクを追加
     *
     * @return void
     */
    #[On('add')]
    public function add(): void
    {
        $this->validate();

        try {
            $command = new ProjectCommand(
                projectId: $this->projectId,
                name: $this->name,
                content: $this->content
            );
    
            $this->addTask->execute($command);
            
            $this->setupView($this->developmentId);

            $this->notify(Message::createSavedMessage());

            $this->reset(['name', 'content']);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
            
            $this->setupView($this->developmentId);
        }
    }
}