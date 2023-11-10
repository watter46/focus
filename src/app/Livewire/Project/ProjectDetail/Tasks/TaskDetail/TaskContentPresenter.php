<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\ContentCommand;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;

final class TaskContentPresenter
{
    private Collection $formatted;
    // private Formatted $formatted;

    public function __construct(private ContentCommand $command)
    {
        // $this->formatted = new Formatted;
    }

    // public function execute(string $content): Collection
    // {
    //     $contents = collect(Str::of($content)->explode("\n"));

    //     $contents->each(function ($content, $index) use ($contents) {  
            
    //         $formatter = $this->command->execute($content);

    //         $formatted = $formatter->format($content, $this->formatted, $index, $contents);
            
    //         $this->formatted->update($formatted);
    //     });

    //     return $this->formatted->get();
    // }

    public function execute(string $content): Collection
    {
        $contents = collect(Str::of($content)->explode("\n"));

        $contents->each(function ($content, $index) use ($contents) {  

            $formatted = $this->formatted ?? collect();
            
            $formatter = $this->command->execute($content);

            $this->formatted = $formatter->format($content, $formatted, $index, $contents);
        });

        return $this->formatted;
    }
}