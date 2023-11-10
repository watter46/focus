<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Splitted;


interface FormatterInterface
{
    public function format(
        string $content,
        Formatted $formatted,
        int $index,
        Splitted $contents): Formatted;
    
    public function supports(string $content): bool;
}
