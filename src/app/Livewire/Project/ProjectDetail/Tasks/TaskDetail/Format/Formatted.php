<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\Format;

use Illuminate\Support\Collection;


final class Formatted
{
    private Collection $list;

    public const UNCHECKED = '- [ ] ';
    public const CHECKED   = '- [|] ';
    
    public function __construct()
    {
        $this->list = collect();
    }
    
    /**
     * フォーマットしたコレクションを更新する
     *
     * @param  Formatted $list
     * @return void
     */
    public function update(Formatted $list): void
    {
        $this->list = $list->toCollection();
    }
    
    /**
     * コレクションに変換する
     *
     * @return Collection
     */
    public function toCollection(): Collection
    {
        return $this->list;
    }
    
    /**
     * 末尾に追加する
     *
     * @param  Collection $content
     * @return self
     */
    public function add(Collection $content): self
    {
        $this->list->add($content);

        return $this;
    }
        
    /**
     * 直前のコマンドのインデックスを取得する
     *
     * @return int
     */
    private function previousCommandIndex(): int
    {
        return $this
                    ->list
                    ->reverse()
                    ->search(function (Collection $content) {
                        return $content->has('ul');
                    });
    }

    /**
     * すでにあるulグループにコマンドを追加する
     *
     * @param  Collection $command
     * @return self
     */
    public function addCommand(Collection $command): self
    {
        $this->list->transform(function (Collection $ul, int $index) use ($command) {
            $shouldAppend = $index === $this->previousCommandIndex();

            if (!$shouldAppend) {
                return $ul;
            }

            $ul->transform(function (Collection $content) use ($command) {
                return $content->push($command);
            });

            return $ul;
        });

        return $this;
    }

    /**
     * 直前のコマンドにコメントを追加
     *
     * @param  string $comment
     * @return Formatted $task;
     */
    public function addComment(string $comment): Formatted
    {
        $this
            ->list
            ->transform(function (Collection $ul, int $index) use ($comment) {
                $previousCommandIndex = $this->previousCommandIndex();

                $shouldAppend = $index === $previousCommandIndex;

                if (!$shouldAppend) {
                    return $ul;
                }

                // 上がコメントの場合
                $hasComments = $this
                    ->list[$previousCommandIndex]
                    ->get('ul')
                    ->last()
                    ->has('comments');

                if ($hasComments) {
                    $ul->transform(function (Collection $content) use ($comment) {
                        $content
                            ->last()
                            ->get('comments')
                            ->push($comment);
                        
                        return $content;
                    });
                    
                    return $ul;
                }

                // 上がコマンドの場合
                $ul->transform(function (Collection $content) use ($comment) {
                    $content
                        ->last()
                        ->put('comments', collect($comment));

                    return $content;
                });

                return $ul;
        });

        return $this;
    }
}