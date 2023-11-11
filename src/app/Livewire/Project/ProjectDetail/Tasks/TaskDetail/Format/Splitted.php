<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;


final class Splitted
{
    public const UNCHECKED = '- [ ] ';
    public const CHECKED   = '- [|] ';

    private Collection $list;
    
    public function __construct()
    {
        //
    }

    public function split(string $content): self
    {
        $this->list = collect(Str::of($content)->explode("\n"));

        return $this;
    }

    public function toCollection(): Collection
    {
        return $this->list;
    }

    /**
     * コマンドをグループに追加できるか判定する
     *
     * @param  int $index
     * @return bool
     */
    public function canAddCommand(int $index): bool
    {
        $list = clone $this->list;

        $list->splice($index);

        foreach ($list->reverse() as $content) {
            if ($list->isEmpty()) {
                break;
            }

            if ($content === '') {
                break;
            }
            
            if ($this->isCommand($content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * コマンド内にコメントを追加できるか判定する
     *
     * @param  int $index
     * @return bool
     */
    public function canAddComment(int $index): bool
    {
        if ($this->list->isEmpty()) {
            return false;
        }

        $copied = clone $this->list;

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
     * コマンドか判定
     *
     * @param  string $task
     * @return bool
     */
    private function isCommand(string $task): bool
    {
        return Str::startsWith($task, [
            self::UNCHECKED,
            self::CHECKED
        ]);
    }
}