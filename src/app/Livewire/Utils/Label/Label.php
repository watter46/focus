<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label;

use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\Type;
use App\Livewire\Utils\Label\Enum\Shape;
use Exception;

final readonly class Label
{
    private function __construct(private Shape $shape)
    {
        //
    }
    
    /**
     * ラベルを表示する用
     *
     * @return self
     */
    public static function Display(): self
    {
        return new self(Shape::Rounded);
    }
    
    /**
     * ラベルをソートする用
     *
     * @return self
     */
    public static function Sort(): self
    {
        return new self(Shape::Circle);
    }
    
    /**
     * ラベルを選択する用
     *
     * @return self
     */
    public static function Select(): self
    {
        return new self(Shape::Circle);
    }

    /**
     * ラベル一覧を取得する
     *
     * @return Collection
     */
    public function labels(): Collection
    {
        return collect(Type::cases())
                ->reject(fn(Type $type) => $type === $type::Unselected)
                ->map(fn(Type $type) => $this->viewData($type));
    }
    
    /**
     * デフォルトのラベルを生成する
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
     * 指定のラベルからViewDataを生成する
     *
     * @param  string $selectedLabel
     * @return Collection
     */
    public function of(string $selectedLabel): Collection
    {
        $label = Type::tryFrom($selectedLabel);
        
        $this->isValidOrThrow($label);
        
        return $this->viewData($label);
    }

    /**
     * ラベルを変更する
     *
     * @param  string $currentLabel
     * @param  string $selectedLabel
     * @return Collection
     */
    public function change(string $currentLabel, string $selectedLabel): Collection
    {
        $current  = Type::tryFrom($currentLabel);
        $selected = Type::tryFrom($selectedLabel);

        $this->isValidOrThrow($current, $selected);
        
        $isSame = $current === $selected;

        $label = $isSame
                    ? Type::Unselected
                    : $selected;

        return $this->viewData($label);
    }
    
    /**
     * viewDataを生成する
     *
     * @param  Type $label
     * @return Collection
     */
    private function viewData(Type $label): Collection
    {
        if ($label === Type::Unselected) {
            return $this->unselected();
        }

        return collect([
            'text'  => $label->value,
            'class' => $label->colorCss().' '.$this->shape->css()
        ]);
    }
    
    /**
     * 無効な値の時例外を投げる
     *
     * @param  ?Type ...$labels
     * @return void
     */
    private function isValidOrThrow(?Type ...$labels): void
    {
        $isValid = collect($labels)->every(fn($label) => $label);

        if ($isValid) return;

        throw new Exception('不正なラベルタイプです。');
    }
}