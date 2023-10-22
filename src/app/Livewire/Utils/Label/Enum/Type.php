<?php declare(strict_types=1);

namespace App\Livewire\Utils\Label\Enum;


enum Type: string
{
    case None       = 'None';
    case Todo       = 'Todo';
    case Fix        = 'Fix';
    case Idea       = 'Idea';
    case Research   = 'Research';
    case Other      = 'Other';
    case Unselected = '';

    public function colorCss(): string
    {
        return match($this) {
            self::None     => 'none-label',
            self::Todo     => 'todo-label',
            self::Fix      => 'fix-label',
            self::Idea     => 'idea-label',
            self::Research => 'research-label',
            self::Other    => 'other-label'
        };
    }
}
