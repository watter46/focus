<?php declare(strict_types=1);

namespace App\Livewire\Utils\Message;

use App\Livewire\Utils\Message\MessageType;

final readonly class Message
{    
    const MESSAGE_KEY = 'message';
    const TYPE_KEY    = 'type';
    
    const SAVED_KEY = 'message';
    const ERROR_KEY = 'error';

    const SAVED_MESSAGE = 'Saved';

    private function __construct(
        private MessageType $type,
        private ?string     $message
    ) {}
    
    /**
     * Savedメッセージを作成する
     *
     * @return self
     */
    public static function createSavedMessage(): self
    {
        return new self(MessageType::Saved, null);
    }
    
    /**
     * Errorメッセージを作成する
     *
     * @param  string $message
     * @return self
     */
    public static function createErrorMessage(string $message): self
    {
        return new self(MessageType::Error, $message);
    }
    
    /**
     * タイプがエラーか判定する
     *
     * @return void
     */
    public function isError()
    {
        return $this->type->isError();
    }

    /**
     * 指定のメッセージを配列にして取得する
     *
     * @return array
     */
    public function toArray(): array
    {
        return match ($this->type) {
            MessageType::Saved => $this->generateArray(self::SAVED_MESSAGE),
            MessageType::Error => $this->generateArray($this->message)
        };
    }
    
    /**
     * 配列を生成する
     *
     * @param  string $message
     * @return array
     */
    private function generateArray(string $message): array
    {
        return [
            self::TYPE_KEY    => $this->type->name,
            self::MESSAGE_KEY => $message
        ];
    }
}
