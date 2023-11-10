<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;


interface FormatterInterface
{
    public function format(
        string $content,
        Collection $formatted,
        int $index,
        Collection $contents): Collection;
    
    public function supports(string $content): bool;
}
