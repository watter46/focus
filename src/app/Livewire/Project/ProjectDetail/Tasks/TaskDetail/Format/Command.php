<?php

declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format\FormatInterface;


final class Command implements FormatInterface
{
    public const UNCHECKED_COMMAND = '- [ ] ';
    public const CHECKED_COMMAND   = '- [|] ';
    public const COMMAND_LENGTH = 6;

    /**
     * コマンドを変換
     *
     * @param  string     $task
     * @param  Collection $formatted
     * @param  int        $index
     * @param  Collection $tasks
     * @return Collection
     */
    public function format(string $task, Collection $formatted, int $index, Collection $tasks): Collection
    {
        if ($this->isSameGroup($index, $tasks)) {
            return $this->groupAppend($task, $formatted);
        }

        $result = collect(['ul' => collect([
            $this->formatCommand($task)
        ])]);

        return $formatted->push($result);
    }

    /**
     * コマンドか判定
     *
     * @param  string $task
     * @return bool
     */
    public function is(string $task): bool
    {
        return Str::startsWith($task, [
            self::UNCHECKED_COMMAND,
            self::CHECKED_COMMAND
        ]);
    }

    /**
     * コマンドを変換
     *
     * @param  string $task
     * @return Collection
     */
    public function formatCommand(string $task): Collection
    {
        $command = Str::of($task)->substr(0, self::COMMAND_LENGTH)->toString();

        return match ($command) {
            self::UNCHECKED_COMMAND => collect([
                'command' => Str::after($task, self::UNCHECKED_COMMAND),
                'isChecked' => false
            ]),
            self::CHECKED_COMMAND => collect([
                'command' => Str::after($task, self::CHECKED_COMMAND),
                'isChecked' => true
            ])
        };
    }

    /**
     * コマンドを同じコレクションに追加できるか判定する
     *
     * @param  int        $index
     * @param  Collection $tasks
     * @return bool
     */
    public function isSameGroup(int $index, Collection $tasks): bool
    {
        $copied = clone $tasks;

        $copied->splice($index);

        foreach ($copied->reverse() as $task) {
            if ($copied->isEmpty()) {
                break;
            }
            if ($task === '') {
                break;
            }
            if ($this->is($task)) {
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
        return $formatted->reverse()->search(function ($task) {
            return Arr::has($task, 'ul');
        });
    }

    /**
     * すでにあるulグループにコマンドを追加する
     *
     * @param  string     $task
     * @param  Collection $formatted
     * @return Collection
     */
    private function groupAppend(string $task, Collection $formatted): Collection
    {
        $command = $this->formatCommand($task);

        return $formatted->transform(function ($task, $index) use ($command, $formatted) {
            $shouldAppend = $index === $this->findPreviousCommandIndex($formatted);

            if (!$shouldAppend) {
                return $task;
            }

            return $task->transform(function ($task) use ($command) {
                return $task->push($command);
            });
        });
    }
}
