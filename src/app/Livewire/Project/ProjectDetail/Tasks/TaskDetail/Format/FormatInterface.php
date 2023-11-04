<?php

declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;


interface FormatInterface
{
    public function format(string $task, Collection $formatted, int $index, Collection $tasks): Collection;
}
