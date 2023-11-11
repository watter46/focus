<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter;

use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatted;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Splitted;


final class NewlineFormatter implements FormatterInterface
{
    /**
     * 改行を変換
     *
     * @param  string    $content
     * @param  Formatted $formatted
     * @param  int       $index
     * @param  Splitted  $splitted
     * @return Formatted
     */
    public function format(string $content, Formatted $formatted, int $index, Splitted $splitted): Formatted
    {
        $newline = collect(['newline' => $content]);

        return $formatted->add($newline);
    }
    
    /**
     * フォーマッタに対応しているか判定する
     *
     * @param  string $content
     * @return bool
     */
    public function supports(string $content): bool
    {
        return Str::of($content)->isEmpty();
    }
}