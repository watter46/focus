<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Illuminate\Support\Collection;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\ContentCommand;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Splitted;


final class TaskContentPresenter
{
    public function __construct(
        private Formatted $formatted,
        private Splitted  $splitted,
        private ContentCommand $command
    ) {
        //
    }

    public function execute(string $content): Collection
    {
        $splitted = $this->splitted->split($content);

        $splitted
            ->toCollection()
            ->each(function (string $content, int $index) use ($splitted) {  
            
            $formatter = $this->command->execute($content);

            $formatted = $formatter->format($content, $this->formatted, $index, $splitted);
            
            $this->formatted->update($formatted);
        });

        return $this->formatted->toCollection();
    }
}