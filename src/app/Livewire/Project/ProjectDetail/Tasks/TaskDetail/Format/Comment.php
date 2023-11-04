<?php

declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatInterface;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\Command;


final class Comment implements FormatInterface
{
    /**
     * コメントを変換
     *
     * @param  string     $task
     * @param  Collection $formatted
     * @param  int        $index
     * @param  Collection $tasks
     * @return Collection
     */
    public function format(string $task, Collection $formatted, int $index, Collection $tasks): Collection
    {
        if (!$this->canAppend($index, $tasks)) {
            return $formatted->push(collect(['comment' => $task]));
        };

        return $this->append($task, $formatted, $tasks);
    }

    /**
     * コマンド内にコメントを追加できるか判定
     *
     * @param  int        $index
     * @param  Collection $tasks
     * @return bool
     */
    private function canAppend(int $index, Collection $tasks): bool
    {
        if ($tasks->isEmpty()) {
            return false;
        }

        $copied = clone $tasks;
        $copied->splice($index);

        foreach ($copied->reverse() as $task) {
            if (Str::of($task)->isEmpty()) {
                return false;
                break;
            }

            $command = new Command();

            if ($command->is($task)) {
                return true;
                break;
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
        return $formatted->reverse()->search(function ($task) {
            return Arr::has($task, 'ul');
        });
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
        return $formatted->transform(function ($ul, int $index) use ($comment, $formatted) {
            $previous_command_index = $this->findPreviousCommandIndex($formatted);

            $shouldAppend = $index === $previous_command_index;

            if (!$shouldAppend) {
                return $ul;
            }

            $last = $formatted[$previous_command_index]
                ->get('ul')
                ->last();

            $has_comments = $last->has('comments');

            if ($has_comments) {
                return $ul->map(function ($ul) use ($comment) {
                    $ul->last()->get('comments')->push(['comment' => $comment]);

                    return $ul;
                });
            }

            $result = $ul->map(function (Collection $task) use ($comment) {
                return $task->transform(function (Collection $detail, int $index) use ($task, $comment) {
                    $is_last = $index === $task->count() - 1;

                    if ($is_last) {
                        $detail->put('comments', collect([['comment' => $comment]]));

                        return $detail;
                    }

                    return $detail;
                });
            });

            return $result;
        });
    }
}
