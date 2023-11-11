<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use Illuminate\Support\Collection;
use Livewire\Form;

use App\Livewire\Project\Projects\Progress\ProgressType;
use App\Livewire\Utils\Label\Enum\LabelType;


final class ProjectsForm extends Form
{
    public Collection $label;
    public Collection $progress;
    public Collection $LABELS;

    public Options $options;

    public function __construct()
    {
        $this->progress = collect(ProgressType::Unselected);
        $this->options = new Options();
    }

    /**
     * Progressの選択数を1以下に保つ
     *
     * @return void
     */
    public function forceProgressLimit(): void
    {
        if ($this->progress->count() > 1) {
            $this->progress->shift();
        }
    }
    
    /**
     * ラベルをセットする
     *
     * @param  LabelType $label
     * @return void
     */
    public function setLabel(LabelType $label): void
    {
        $this->options->setLabel($label);
    }
    
    /**
     * 進捗をセットする
     *
     * @param  ProgressType $progress
     * @return void
     */
    public function setProgress(ProgressType $progress): void
    {
        $this->options->setProgress($progress);
    }
}