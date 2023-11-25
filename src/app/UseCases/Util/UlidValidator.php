<?php declare(strict_types=1);

namespace App\UseCases\Util;

use Exception;
use Illuminate\Support\Str;


final readonly class UlidValidator
{
    public static function isValid(string $id): bool
    {
        if (!Str::isUlid($id)) {
            throw new Exception('IDが無効です。');
        }

        return true;
    }
}
