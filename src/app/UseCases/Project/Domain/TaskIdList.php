<?php declare(strict_types=1);

namespace App\UseCases\Project\Domain;

use Illuminate\Support\Collection;


final readonly class TaskIdList
{
    const MAX_TASK_COUNT = 10;
    const TASK_LIMIT_EXCEEDED_MESSAGE = 'タスクの最大数は'.self::MAX_TASK_COUNT.'です。';

    private function __construct(private Collection $list)
    {
        //
    }

    public static function create(Collection $list): self
    {
        return new self($list);
    }

    public function canAddTask(): bool
    {
        return $this->list->count() < self::MAX_TASK_COUNT;
    }
}