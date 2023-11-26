<?php declare(strict_types=1);

namespace App\UseCases\Task;


final readonly class TaskCommand
{
    private function __construct(
        private string  $taskId,
        private ?string $name = null,
        private ?string $content = null
    ) {   
        //
    }

    public static function find(string $taskId): self
    {
        return new self(taskId: $taskId);
    }

    public static function update(
        string $taskId,
        string $name,
        string $content): self
    {
        return new self(
            taskId:  $taskId,
            name:    $name,
            content: $content
        );
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
