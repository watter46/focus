<?php declare(strict_types=1);

namespace App\Livewire\Development\TaskSelector;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Task;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\ChangeTaskUseCase;
use App\UseCases\Development\FetchRemainingTasksUseCase;


final class ChangeTask extends Component
{
    #[Locked]
    public string $developmentId;

    public array $selectedIds;
    public int   $taskCount;

    public array $additionalIdList = [];

    private readonly ChangeTaskUseCase $changeTask;
    private readonly FetchRemainingTasksUseCase $fetchRemainingTasks;

    public function boot(
        ChangeTaskUseCase $changeTask,
        FetchRemainingTasksUseCase $fetchRemainingTasks
        
    ) {
        $this->changeTask = $changeTask;
        $this->fetchRemainingTasks = $fetchRemainingTasks;
    }

    public function render()
    {
        return view('livewire.development.task-selector.change-task', [
            'remainingTasks' => $this->fetchRemainingTasks()
        ]);
    }
    
    /**
     * 残りのタスクを取得する
     *
     * @return Collection<int, Task>
     */
    private function fetchRemainingTasks(): Collection
    {
        $command = new DevelopmentCommand(developmentId: $this->developmentId);

        return $this->fetchRemainingTasks->execute($command);
    }
    
    /**
     * idのリストを変更する
     *
     * @param  string $id
     * @param  bool   $shouldAdd
     * @return void
     */
    public function change(string $id, bool $shouldAdd): void
    {
        $additionalIdList = collect($this->additionalIdList);
        
        $list = $shouldAdd
            ? $additionalIdList->push($id)
            : $additionalIdList->reject(fn($item) => $item === $id );

        $this->additionalIdList = $list->toArray();
    }
    
    /**
     * 開発するタスクIDリストを保存
     *
     * @return void
     */
    public function save(): void
    {
        try {
            if (!$this->additionalIdList) return;

            $command = new DevelopmentCommand(
                        developmentId: $this->developmentId,
                        selectedIdList: $this->additionalIdList
                    );
            
            $this->changeTask->execute($command);
                    
            $this->dispatch('refetch')->to(InprogressTasks::class);

            $this->dispatch('close-change-task');

            $this->notify(Message::createSavedMessage());

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}