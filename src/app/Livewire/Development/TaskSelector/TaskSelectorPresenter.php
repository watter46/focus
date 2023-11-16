<?php declare(strict_types=1);

namespace App\Livewire\Development\TaskSelector;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Models\Task;
use App\UseCases\Task\FetchIncompleteTasks\FetchIncompleteTasks;


final readonly class TaskSelectorPresenter
{
    public string $projectId;

    public const CHECKED   = '[|]';
    public const UNCHECKED = '[ ]';
    
    public function __construct(private FetchIncompleteTasks $fetchIncompleteTasks)
    {
        //
    }

    /**
     * InCompleteのView用
     *
     * @param  string $projectId
     * @return Collection<int, array{id: string, title: string, count: int}>
     */
    public function execute(string $projectId): Collection
    {
        $tasks = $this->fetchIncompleteTasks($projectId);

        return $this->format($tasks);
    }

    /**
     * ProjectIdから未完了のタスクを取得
     *
     * @param  string $projectId
     * @return Collection<int, Task>
     */
    private function fetchIncompleteTasks(string $projectId): Collection
    {
        return $this->fetchIncompleteTasks->execute($projectId);
    }

    /**
     * View表示に必要なデータに加工する
     *
     * @param  Collection<int, EqTask> $tasks
     * @return Collection
     */
    private function format(Collection $tasks): Collection
    {
        return $tasks->map(function (Task $task) {
            return collect([
                'id'    => $task->id,
                'title' => $task->name,
                'count' => $this->count($task->content)
            ]);
        });
    }

    /**
     * タスク内容のcheckboxの数をカウントする
     *
     * @param  string $task
     * @return int
     */
    private function count(string $task): int
    {
        $checkedCount   = Str::substrCount($task, self::CHECKED);
        $uncheckedCount = Str::substrCount($task, self::UNCHECKED);

        return $checkedCount + $uncheckedCount;
    }
}
