<?php declare(strict_types=1);

namespace App\Livewire\Development\Timer;


enum StatusType
{
    case Disabled;
    case Ready;
    case Running;
    case Paused;

    /**
     * Disabledか判定
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this === self::Disabled;
    }

    /**
     * Readyか判定
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return $this === self::Ready;
    }

    /**
     * Runningか判定
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this === self::Running;
    }

    /**
     * Pausedか判定
     *
     * @return bool
     */
    public function isPaused(): bool
    {
        return $this === self::Paused;
    }
}