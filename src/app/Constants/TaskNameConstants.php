<?php declare(strict_types=1);

namespace App\Constants;

use Exception;
use Illuminate\Support\Str;


final readonly class TaskNameConstants
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 50;

    public static function isValid(string $name): bool
    {
        $length = Str::length($name);

        $isValid = self::MIN_LENGTH <= $length && $length <= self::MAX_LENGTH;

        if ($isValid) {
            return true;
        }

        throw new Exception('タスク名は'.self::MIN_LENGTH.'から'.self::MAX_LENGTH.'文字までです。');
    }
}
