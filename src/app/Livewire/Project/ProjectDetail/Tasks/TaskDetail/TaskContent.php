<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Livewire\Component;
use Livewire\Attributes\Locked;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\TaskContentPresenter;


final class TaskContent extends Component
{
    #[Locked]
    public string $taskId;

    #[Locked]
    public string $projectId;

    public string $content;

    private readonly TaskContentPresenter $presenter;

    public function boot(TaskContentPresenter $presenter)
    {
        $this->presenter  = $presenter;
    }

    public function render()
    {
        return view('livewire.project.project-detail.tasks.task-detail.task-content', [
            'tasks' => $this->presenter->formatTask($this->content)
        ]);
    }
}