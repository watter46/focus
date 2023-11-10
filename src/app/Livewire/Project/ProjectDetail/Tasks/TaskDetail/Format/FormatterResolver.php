<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;


final class FormatterResolver
{
    /** @var Collection<int, FormatterInterface> $formatters */
    private Collection $formatters;
    
    public function __construct()
    {
        $this->formatters = collect();
    }

    public function add(FormatterInterface $formatter): self
    {
        $this->formatters->push($formatter);
        
        return $this;
    }

    public function resolve(string $content): FormatterInterface
    {
        foreach($this->formatters as $formatter) {
            if ($formatter->supports($content)) {
                return $formatter;
            }
        }
    }
}
