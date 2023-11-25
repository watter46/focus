<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Exception;
use Illuminate\Support\Carbon;


final readonly class FinishedAt
{        
    /**
     * __construct
     *
     * @param  Carbon $startedAt
     * @return void
     */
    private function __construct(private Carbon $finishedAt)
    {
        //
    }

    public static function create(string $finishedAt): self
    {
        return new self(Carbon::make($finishedAt));
    }

    public static function finish(): self
    {
        return new self(now());
    }

    public function value(): Carbon
    {
        return $this->finishedAt;
    }
}
