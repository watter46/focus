<?php declare(strict_types=1);

namespace App\Constants;

use Exception;

use App\Constants\DefaultTimeConstants;


final readonly class RemainingTimeConstants
{
    const MIN_sec = DefaultTimeConstants::MIN_sec;
    const MAX_sec = DefaultTimeConstants::MAX_sec;

    const MIN_min = DefaultTimeConstants::MIN_min;
    const MAX_min = DefaultTimeConstants::MAX_min;
    
    const OUT_OF_RANGE_EXCEPTION = '残り時間は'.self::MIN_min.'分から'.self::MAX_min.'分の間です。';
    
    public static function isValid(int $remainingTime_sec, int $defaultTime_sec): bool
    {
        $isValid = self::MIN_sec <= $remainingTime_sec && $remainingTime_sec <= self::MAX_sec;
        
        if (!$isValid) {
            throw new Exception(self::OUT_OF_RANGE_EXCEPTION);
        }

        if ($defaultTime_sec < $remainingTime_sec) {
            throw new Exception('残り時間が不正な値です。');
        }

        return true;
    }
}
