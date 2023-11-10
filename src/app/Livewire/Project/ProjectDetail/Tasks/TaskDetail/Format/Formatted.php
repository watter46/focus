<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;


final class Formatted
{
    private Collection $list;
    
    public function __construct()
    {
        $this->list = collect();
    }

    public function update(Formatted $list): void
    {
        $this->list = $list;
    }

    public function get(): Collection
    {
        return $this->list;
    }
    
    public function findPreviousCommandIndex(Collection $list): int
    {
        return $list
                ->reverse()
                ->search(function (Collection $content) {
                    return $content->has('ul');
                });
    }
}
