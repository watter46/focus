<?php

declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatInterface;


final class Newline implements FormatInterface
{
    /**
     * 改行を変換
     *
     * @param  string     $task
     * @param  Collection $formatted
     * @param  int        $index
     * @param  Collection $tasks
     * @return Collection
     */
    public function format(string $task, Collection $formatted, int $index, Collection $tasks): Collection
    {
        $result = collect(['newline' => '']);

        return $formatted->push($result);
    }

    public function is(string $task): bool
    {
        return Str::of($task)->isEmpty();
    }
}
