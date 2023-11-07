<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\ReadLabelInterface;


interface LabelInterface extends ReadLabelInterface
{
    /**
     * ラベル一覧を取得する
     *
     * @return Collection
     */
    public function labels(): Collection;

    /**
     * ラベルを変更する
     *
     * @param  Collection $currentLabel
     * @param  string $selectedLabel
     * @return LabelType
     */
    public function change(Collection $currentLabel, string $selectedLabel): LabelType;
    
    /**
     * デフォルトのラベルを設定する
     *
     * @return Collection
     */
    public function defaultLabel(): Collection;
    
    /**
     * 用途に対応しているプレゼンターか判定する
     *
     * @param  PurposeType $purpose
     * @return bool
     */
    public function supports(PurposeType $purpose): bool;
}
