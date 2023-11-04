<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Str;


final readonly class TaskCommand
{
    public function __construct(
        private string  $taskId,
        private ?string $name = null,
        private ?string $content = null
    ) {   
        if (!Str::isUlid($this->taskId)) {
            throw new Exception('プロジェクトIDが無効です。');
        }
    }

    public function taskId(): string
    {
        return $this->taskId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function content(): ?string
    {
        return $this->content;
    }
}
