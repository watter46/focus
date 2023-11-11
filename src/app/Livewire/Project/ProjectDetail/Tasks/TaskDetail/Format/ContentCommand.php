<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter\CommandFormatter;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter\CommentFormatter;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter\NewlineFormatter;


final class ContentCommand
{
    public function __construct(private readonly FormatterResolver $resolver)
    {
        $resolver
            ->add(new CommandFormatter)
            ->add(new CommentFormatter)
            ->add(new NewlineFormatter);
    }

    public function execute(string $content): FormatterInterface
    {
        return $this->resolver->resolve($content);
    }
}