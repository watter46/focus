<?php declare(strict_types=1);

namespace App\Livewire\Development\TaskSelector;

use Livewire\Component;
use Livewire\Attributes\Locked;
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
}