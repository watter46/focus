<?php declare(strict_types=1);

namespace App\Constants;

use Exception;
use Illuminate\Support\Str;


final readonly class TaskContentConstants
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 500;

    public static function isValid(string $content): bool
    {
        $length = Str::length($content);

        $isValid = self::MIN_LENGTH <= $length && $length <= self::MAX_LENGTH;

        if ($isValid) {
            return true;
        }

        throw new Exception('タスク内容は'.self::MIN_LENGTH.'から'.self::MAX_LENGTH.'文字までです。');
    }
}
