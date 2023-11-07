<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\Enum\ShapeType;


interface ReadLabelInterface
{
    /**
     * viewDataを生成する
     *
     * @param  LabelType $label
     * @return Collection
     */
    public function toViewData(LabelType $label): Collection;

    /**
     * 用途に対応しているプレゼンターか判定する
     *
     * @param  PurposeType $purpose
     * @return bool
     */
    public function supports(PurposeType $purpose): bool;
    
    /**
     * ラベルの形をきめる
     *
     * @return ShapeType
     */
    public function shape(): ShapeType;
}
