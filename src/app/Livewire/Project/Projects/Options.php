<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use Illuminate\Support\Collection;
use Livewire\Wireable;

use App\Livewire\Project\Projects\Progress\ProgressType;
use App\Livewire\Utils\Label\Enum\LabelType;


final class Options implements Wireable
{        
    public Collection $options;
    
    /**
     * __construct
     * 
     * @param Collection<array{label: LabelType, progress: ProgressType}> $options
     * @return void
     */
    public function __construct(
        Collection $options = new Collection([
            'label'    => LabelType::Unselected,
            'progress' => ProgressType::Unselected
        ]))
    {
        $this->options = $options;
    }
    
    public function toLivewire()
    {
        return $this
                ->options
                ->map(fn($type) => $type->value);
    }
    
    public static function fromLivewire($value)
    {
        $label    = LabelType::from($value['label']);
        $progress = ProgressType::from($value['progress']);
        
        return new static(collect([
            'label'    => $label,
            'progress' => $progress
        ]));
    }

    public function getLabel(): LabelType
    {
        return $this->options->get('label');
    }

    public function getProgress(): ProgressType
    {
        return $this->options->get('progress');
    }
    
    public function setLabel(LabelType $label): void
    {
        $this->options->put('label', $label);
    }

    public function setProgress(ProgressType $progress): void
    {
        $this->options->put('progress', $progress);
    }
}