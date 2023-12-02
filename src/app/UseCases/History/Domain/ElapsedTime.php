<?php declare(strict_types=1);

namespace App\UseCases\History\Domain;

use Exception;

use App\UseCases\Development\Domain\Development;


final readonly class ElapsedTime
{
    private function __construct(private int $elapsedTime)
    {
        if ($elapsedTime < 0) {
            throw new Exception('経過時間が不正な値です。');
        }
    }

    public static function create(Development $development): self
    {
        $timer = $development->timer();
        
        $elapsedTime = $timer->defaultTime() - $timer->remainingTime();
        
        return new self($elapsedTime);
    }

    public function value(): int
    {
        return $this->elapsedTime;
    }
}