<?php declare(strict_types=1);

namespace App\UseCases\Development\Domain;

use Exception;

use App\Constants\DefaultTimeConstants;
use App\Constants\RemainingTimeConstants;
use App\UseCases\Development\Domain\StartedAt;
use App\UseCases\Development\Domain\FinishedAt;


final readonly class Timer
{
    private ?int        $defaultTime;
    private ?int        $remainingTime;
    private ?StartedAt  $startedAt;
    private ?FinishedAt $finishedAt;

    public function __construct(
        ?int        $defaultTime   = null,
        ?int        $remainingTime = null,
        ?StartedAt  $startedAt     = null,
        ?FinishedAt $finishedAt    = null,
    ) {
        $this->defaultTime   = $defaultTime;
        $this->remainingTime = $remainingTime;
        $this->startedAt     = $startedAt;
        $this->finishedAt    = $finishedAt;
    }

    private function validate(): bool
    {
        DefaultTimeConstants::isValid_sec($this->defaultTime);
        RemainingTimeConstants::isValid($this->remainingTime, $this->defaultTime);

        $this->isFinishedAfterStart();
        
        return true;
    }

    private function isFinishedAfterStart(): void
    {
        if (!$this->startedAt)  return;
        if (!$this->finishedAt) return;

        $isValid = $this->startedAt->value()->lt($this->finishedAt->value());

        if (!$isValid) {
            throw new Exception('終了時間が不正な値です。');
        }
    }
    
    /**
     * タイマーをスタートする
     *
     * @param  int $defaultTime
     * @return self
     */
    public function start(int $defaultTime): self
    {
        return $this->changeAttribute(
            defaultTime: $defaultTime,
            remainingTime: $defaultTime,
            startedAt: StartedAt::start()
        );
    }
    
    /**
     * タイマーを止める
     *
     * @param  int $remainingTime
     * @return self
     */
    public function stop(int $remainingTime): self
    {
        return $this->changeAttribute(remainingTime: $remainingTime);
    }
    
    /**
     * タイマーを完了する
     *
     * @return self
     */
    public function complete(): self
    {
        return $this->changeAttribute(
            remainingTime: 0,
            finishedAt: FinishedAt::finish()
        );
    }
    
    /**
     * タイマーをクリアする
     *
     * @return self
     */
    public function clear(): self
    {
        return $this->changeAttribute(finishedAt: FinishedAt::finish());
    }
    
    /**
     * プロパティを変更する
     * 
     * @param ?int        $defaultTime,
     * @param ?int        $remainingTime,
     * @param ?StartedAt  $startedAt,
     * @param ?FinishedAt $finishedAt
     * 
     * @return self
     */
    private function changeAttribute(
        ?int        $defaultTime   = null,
        ?int        $remainingTime = null,
        ?StartedAt  $startedAt     = null,
        ?FinishedAt $finishedAt    = null): self
    {
        $this->validate();
        
        return new self(
            defaultTime:   $defaultTime   ?? $this->defaultTime,
            remainingTime: $remainingTime ?? $this->remainingTime,
            startedAt:     $startedAt     ?? $this->startedAt,
            finishedAt:    $finishedAt    ?? $this->finishedAt,
        );
    }

    public function defaultTime(): int
    {
        return $this->defaultTime;
    }

    public function remainingTime(): int
    {
        return $this->remainingTime;
    }

    public function startedAt(): ?StartedAt
    {
        return $this->startedAt;
    }

    public function finishedAt(): ?FinishedAt
    {
        return $this->finishedAt;
    }
}
