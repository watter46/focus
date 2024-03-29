<?php declare(strict_types=1);

namespace App\Constants;

use Exception;


final readonly class BreakTimeConstants
{
    const VALUE_min = 5;
    const MIN_min   = 1;
    const MAX_min   = 30;

    const VALUE_sec = self::VALUE_min * 60;
    const MIN_sec   = self::MIN_min * 60;
    const MAX_sec   = self::MAX_min * 60;

    const OUT_OF_RANGE_EXCEPTION = '休憩時間は'.self::MIN_min.'分から'.self::MAX_min.'分の間です。';

    public static function isValid_min(int $breakTime): bool
    {
        $isValid = self::MIN_min <= $breakTime && $breakTime <= self::MAX_min;

        return $isValid ?? throw new Exception(self::OUT_OF_RANGE_EXCEPTION);
    }

    public static function isValid_sec(int $breakTime): bool
    {
        $isValid = self::MIN_sec <= $breakTime && $breakTime <= self::MAX_sec;

        return $isValid ?? throw new Exception(self::OUT_OF_RANGE_EXCEPTION);
    }
}