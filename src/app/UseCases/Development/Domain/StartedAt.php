<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Illuminate\Support\Carbon;


final readonly class StartedAt
{        
    /**
     * __construct
     *
     * @param  Carbon $startedAt
     * @return void
     */
    private function __construct(private Carbon $startedAt)
    {
        //
    }

    public static function create(string $startedAt): self
    {
        return new self(Carbon::make($startedAt));
    }

    public static function start(): self
    {
        return new self(now());
    }

    public function value(): Carbon
    {
        return $this->startedAt;
    }
}
