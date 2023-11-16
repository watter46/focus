<?php

declare(strict_types=1);

namespace App\Livewire\Development\Timer;


final class Status
{
    private StatusType $status;

    public function __construct()
    {
        $this->toDisabled();
    }

    public function toDisabled(): void
    {
        $this->status = StatusType::Disabled;
    }

    public function toReady(): void
    {
        $this->status = StatusType::Ready;
    }

    public function toRunning(): void
    {
        $this->status = StatusType::Running;
    }

    public function toPaused(): void
    {
        $this->status = StatusType::Paused;
    }

    

    /**
     * Startボタンをミュートするか判定
     *
     * @return bool
     */
    public function isMuteStartBtn(): bool
    {
        if ($this->status->isDisabled()) {
            return true;
        }

        return false;
    }

    /**
     * Clearボタンをミュートするか判定
     *
     * @return bool
     */
    public function isMuteClearBtn(): bool
    {
        if (!$this->status->isPaused()) {
            return true;
        }

        return false;
    }

    /**
     * TimerSetterボタンをミュートするか判定
     *
     * @param  bool $isStart
     * @return bool
     */
    public function isMuteTimeSetterBtn(bool $isStart): bool
    {
        if ($isStart) {
            return true;
        }

        return false;
    }
}
