<?php declare(strict_types=1);

namespace App\Constants;

use Exception;


final readonly class DefaultTimeConstants
{
    const VALUE_min = 25;
    const MIN_min   = 1;
    const MAX_min   = 90;

    const VALUE_sec = self::VALUE_min * 60;
    // const MIN_sec   = self::MIN_min * 60;
    const MIN_sec   = 1;
    const MAX_sec   = self::MAX_min * 60;

    const OUT_OF_RANGE_EXCEPTION = '設定時間は'.self::MIN_min.'分から'.self::MAX_min.'分の間です。';

    public static function isValid_min(int $defaultTime): bool
    {
        $isValid = self::MIN_min <= $defaultTime && $defaultTime <= self::MAX_min;

        return $isValid ?? throw new Exception(self::OUT_OF_RANGE_EXCEPTION);
    }

    public static function isValid_sec(int $defaultTime): bool
    {
        $isValid = self::MIN_sec <= $defaultTime && $defaultTime <= self::MAX_sec;

        return $isValid ?? throw new Exception(self::OUT_OF_RANGE_EXCEPTION);
    }
}