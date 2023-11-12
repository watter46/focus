<?php declare(strict_types=1);

namespace App\UseCases\Setting;

use App\Models\Setting;


final readonly class SettingCommand
{
    public function __construct(private int $defaultTime, private int $breakTime)
    {
        //
    }

    public function defaultTime(): int
    {
        return $this->defaultTime;
    }

    public function breakTime(): int
    {
        return $this->breakTime;
    }
}
