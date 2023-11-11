<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label\Presenters;

use Exception;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\Enum\ShapeType;
use App\Livewire\Utils\Label\LabelInterface;


final readonly class SortLabelPresenter implements LabelInterface
{    
    public function __construct()
    {
        //
    }

    /**
     * ラベル一覧を取得する
     *
     * @return Collection
     */
    public function labels(): Collection
    {
        return collect(LabelType::cases())
                ->reject(fn(LabelType $type) => $type === $type::Unselected)
                ->map(fn(LabelType $type) => $this->toViewData($type));
    }
    
    /**
     * unselectedのラベルを生成する
     *
     * @return Collection
     */
    public function defaultLabel(): Collection
    {
        return $this->toViewData(LabelType::Unselected);
    }
        
    /**
     * ラベルを変更する
     *
     * @param  Collection $currentLabel
     * @param  string $selectedLabel
     * @return LabelType
     */
    public function change(Collection $currentLabel, string $selectedLabel): LabelType
    {        
        $current  = LabelType::tryFrom($currentLabel->get('text'));
        $selected = LabelType::tryFrom($selectedLabel);

        $this->isValidOrThrow($current, $selected);
        
        return $current === $selected
                    ? LabelType::Unselected
                    : $selected;
    }
    
    /**
     * viewDataを生成する
     *
     * @param  LabelType $label
     * @return Collection
     */
    public function toViewData(LabelType $label): Collection
    {
        if ($label === LabelType::Unselected) {
            return collect([
                'text'  => $label->value,
                'class' => ''
            ]);
        }
        
        return collect([
            'text'  => $label->value,
            'class' => $label->colorCss().' '.$this->shape()->css()
        ]);
    }
    
    /**
     * 無効な値の時例外を投げる
     *
     * @param  ?LabelType ...$labels
     * @return void
     */
    private function isValidOrThrow(?LabelType ...$labels): void
    {
        $isValid = collect($labels)->every(fn($label) => $label);

        if ($isValid) return;

        throw new Exception('不正なラベルタイプです。');
    }

    public function supports(PurposeType $purpose): bool
    {
        return $purpose === PurposeType::sort;
    }

    public function shape(): ShapeType
    {
        return ShapeType::Circle;
    }
}