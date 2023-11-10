<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatterInterface;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Formatter\CommandFormatter;


final class CommentFormatter implements FormatterInterface
{
    /**
     * コメントを変換
     *
     * @param  string     $content
     * @param  Collection $formatted
     * @param  int        $index
     * @param  Collection $contents
     * @return Collection
     */
    public function format(string $content, Collection $formatted, int $index, Collection $contents): Collection
    {
        if (!$this->canAppend($index, $contents)) {
            return $formatted->push(collect(['comment' => $content]));
        };

        return $this->append($content, $formatted, $contents);
    }
    
    /**
     * コメントフォーマットに対応しているか判定する
     *
     * @param  string $content
     * @return bool
     */
    public function supports(string $content): bool
    {
        if ($this->isCommand($content)) {
            return false;
        }
                
        if ($content === '') {
            return false;
        }
        
        return true;
    }

    private function isCommand(string $content): bool
    {
        $pattern = "/^-\x20\[.\]\x20[^\x20]+/";

        return (bool) preg_match($pattern, $content);
    }

    /**
     * コマンド内にコメントを追加できるか判定する
     *
     * @param  int        $index
     * @param  Collection $contents
     * @return bool
     */
    private function canAppend(int $index, Collection $contents): bool
    {
        if ($contents->isEmpty()) {
            return false;
        }

        $copied = clone $contents;

        $copied->splice($index);
        
        foreach ($copied->reverse() as $content) {
            if ($content === '') {
                return false;
            }
            
            if ($this->isCommand($content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * コメントを追加する
     *
     * @param  Collection $formatted
     * @return int
     */
    private function findPreviousCommandIndex(Collection $formatted): int
    {
        return $formatted
                    ->reverse()
                    ->search(fn ($content) => $content->has('ul'));
    }

    /**
     * 直前のコマンドにコメントを追加
     *
     * @param  string     $comment
     * @param  Collection $formatted
     * @return Collection $task;
     */
    private function append(string $comment, Collection $formatted): Collection
    {
        return $formatted->transform(function (Collection $ul, int $index) use ($comment, $formatted) {
            $previousCommandIndex = $this->findPreviousCommandIndex($formatted);

            $shouldAppend = $index === $previousCommandIndex;

            if (!$shouldAppend) {
                return $ul;
            }

            // 上がコメントの場合
            $hasComments = $formatted[$previousCommandIndex]
                ->get('ul')
                ->last()
                ->has('comments');

            if ($hasComments) {
                return $ul->map(function (Collection $ul) use ($comment) {
                    $ul
                        ->last()
                        ->get('comments')
                        ->push(['comment' => $comment]);

                    return $ul;
                });
            }

            // 上がコマンドの場合
            return $ul->map(function (Collection $content) use ($comment) {
                return $content->transform(function (Collection $detail, int $index) use ($content, $comment) {
                    $isLast = $index === $content->count() - 1;

                    if ($isLast) {
                        $detail->put('comments', collect([['comment' => $comment]]));

                        return $detail;
                    }

                    return $detail;
                });
            });
        });
    }
}