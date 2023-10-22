<?php declare(strict_types=1);

namespace App\Livewire\Utils\Message;

enum MessageType
{    
    case Saved;
    case Error;
    
    /**
     * タイプがエラーか判定する
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this === self::Error;
    }
}
