<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use App\Livewire\Utils\Label\Label;
use App\UseCases\Project\SortProjectsUseCase;
use Illuminate\Support\Collection;


trait ProjectsFormableTrait
{
    /**
     * privateにすると、何の型でも許されるせいなのか、
     * ソートしながらページネーションできなくなるのでpublicにする
     *
     * @var Collection<array{label: string, progress: string}> $options
     */
    public Collection $options;
    public Collection $label;
    public Collection $progress;
    public Collection $LABELS;

    private readonly SortProjectsUseCase $sortProjects;

    public function bootProjectsFormableTrait(SortProjectsUseCase $sortProjects)
    {
        $this->sortProjects = $sortProjects;
    }

    public function mountProjectsFormableTrait(): void
    {
        $this->options  = collect(['label' => '', 'progress' => '']);
        $this->label    = Label::Sort()->unselected();
        $this->progress = collect();
        $this->LABELS   = Label::Sort()->labels();
    }
    
     /* Progressの選択数を1以下に保つ
     *
     * @return void
     */
    public function updatedProgress(): void
    {
        if ($this->progress->count() > 1) {
            $this->progress->shift();
        }
    }
}