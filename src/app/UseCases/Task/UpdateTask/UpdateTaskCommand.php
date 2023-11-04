<?php declare(strict_types=1);

namespace App\UseCases\Task\UpdateTask;

final readonly class UpdateTaskCommand
{
    public function __construct(
        private string  $taskId,
        private ?string $name = null,
        private ?string $content = null
    ) {   
        //
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
