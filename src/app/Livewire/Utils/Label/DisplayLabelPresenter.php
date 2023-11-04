<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\ShapeType;


final readonly class DisplayLabelPresenter
{
    const SHAPE_TYPE = ShapeType::Rounded;

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
            'class' => $label->colorCss().' '.self::SHAPE_TYPE->css()
        ]);
    }
}