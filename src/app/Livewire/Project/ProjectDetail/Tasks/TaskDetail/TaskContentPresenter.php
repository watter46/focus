<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Illuminate\Support\Collection;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\TaskFormatter;


final class TaskContentPresenter
{
    /**
     * 表示用にタスクをフォーマットする
     *
     * @param  string $tasks
     */
    public function formatTask(string $tasks): array|Collection
    {
        $formatter = new TaskFormatter($tasks);

        return $formatter->handle();
    }
}
