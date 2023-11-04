<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label\Enum;


enum ShapeType
{
    case Rounded;
    case Circle;

    /**
     * ラベルの形を取得
     *
     * @return string
     */
    public function css(): string
    {
        return match ($this) {
            self::Rounded => 'rounded-label',
            self::Circle  => 'circle-label',
        };
    }
}
