<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Exception;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\ShapeType;


final readonly class SortLabelPresenter
{
    const SHAPE_TYPE = ShapeType::Circle;
    
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
    public function unselected(): Collection
    {
        return collect([
            'text'  => '',
            'class' => ''
        ]);
    }
        
    /**
     * ラベルを変更する
     *
     * @param  Collection $currentLabel
     * @param  string $selectedLabel
     * @return Collection
     */
    public function change(Collection $currentLabel, string $selectedLabel): Collection
    {        
        $current  = LabelType::tryFrom($currentLabel->get('text'));
        $selected = LabelType::tryFrom($selectedLabel);

        $this->isValidOrThrow($current, $selected);
        
        $label = $current === $selected
                ? LabelType::Unselected
                : $selected;
        
        return $this->toViewData($label);
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
            return $this->unselected();
        }
        
        return collect([
            'text'  => $label->value,
            'class' => $label->colorCss().' '.self::SHAPE_TYPE->css()
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
}