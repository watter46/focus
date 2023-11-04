<?php

declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Command;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Newline;


final class TaskFormatter
{
    private array|Collection $formatted = [];

    public function __construct(private readonly string $task)
    {}

    public function handle()
    {
        $tasks = collect(Str::of($this->task)->explode("\n"));

        $tasks->each(function ($task, $index) use ($tasks) {
            $formatted = $this->defaultOrChangeCollection($this->formatted);

            $command = new Command();
            $newline = new NewLine();
            $comment = new Comment();

            if ($command->is($task)) {
                $this->formatted = $command->format($this->unescape($task), $formatted, $index, $tasks);
                return true;
            }

            if ($newline->is($task)) {
                $this->formatted = $newline->format($this->unescape($task), $formatted, $index, $tasks);
                return true;
            }

            $this->formatted = $comment->format($this->unescape($task), $formatted, $index, $tasks);
        });

        return $this->formatted;
    }

    /**
     * 型を判定して、配列ならコレクションに直す、コレクションなら返す
     *
     * @param  array|Collection $formatted
     * @return Collection
     */
    private function defaultOrChangeCollection(array|Collection $formatted): Collection
    {
        return is_array($formatted) ? collect($formatted) : $formatted;
    }

    /**
     * バックスラッシュを取り除く
     *
     * @param  string $task
     * @return string
     */
    private function unescape(string $task): string
    {
        return Str::replace('\\', '', $task);
    }
}
