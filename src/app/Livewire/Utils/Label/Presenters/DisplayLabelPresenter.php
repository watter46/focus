<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label\Presenters;

use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\Enum\ShapeType;
use App\Livewire\Utils\Label\ReadLabelInterface;


final readonly class DisplayLabelPresenter implements ReadLabelInterface
{
    public function __construct()
    {
        //
    }
    
    /**
     * viewDataを生成する
     *
     * @param  LabelType $label
     * @return Collection
     */
    public function toViewData(LabelType $label): Collection
    {
        return collect([
            'text'  => $label->value,
            'class' => $label->colorCss().' '.$this->shape()->css()
        ]);
    }

    public function supports(PurposeType $purpose): bool
    {
        return $purpose === PurposeType::display;
    }

    public function shape(): ShapeType
    {
        return ShapeType::Rounded;
    }
}