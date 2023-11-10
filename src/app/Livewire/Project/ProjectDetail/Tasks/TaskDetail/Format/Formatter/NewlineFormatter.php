<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;


final class NewlineFormatter implements FormatterInterface
{
    /**
     * 改行を変換
     *
     * @param  string     $content
     * @param  Collection $formatted
     * @param  int        $index
     * @param  Collection $contents
     * @return Collection
     */
    public function format(string $content, Collection $formatted, int $index, Collection $contents): Collection
    {
        $result = collect(['newline' => $content]);

        return $formatted->push($result);
    }

    public function supports(string $content): bool
    {
        return Str::of($content)->isEmpty();
    }
}
