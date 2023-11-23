<?php declare(strict_types=1);

namespace App\Livewire\Development\TaskSelector;

use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

use App\Livewire\Development\TaskSelector\TaskSelectorPresenter;


final class TaskSelector extends Component
{
    #[Locked]
    public string $projectId;

    #[Locked]
    public ?string $developmentId;

    public bool $isStart;

    public Collection $titles;

    public function mount(TaskSelectorPresenter $presenter)
    {
        $this->titles = $presenter->execute($this->projectId);
    }

    public function render()
    {
        return view('livewire.development.task-selector.task-selector');
    }

    #[On('development-started')]    
    /**
     * 開発するタスクを表示する
     *
     * @param  string $developmentId
     * @return void
     */
    public function showDevelopmentTask(string $developmentId)
    {
        $this->developmentId = $developmentId;
        
        $this->isStart = true;
    }

    #[On('development-finished')]    
    /**
     * タスク選択画面を表示する
     *
     * @return void
     */
    public function hideDevelopmentTask(): void
    {
        $this->isStart = false;
    }
}