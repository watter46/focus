<?php declare(strict_types=1);

namespace App\UseCases\Project\RegisterTask;

use Exception;
use Illuminate\Support\Str;

use App\Models\Project;


final readonly class TaskInProject
{
    const MAX_TASK_COUNT = 10;

    public function __construct(
        private string  $name,
        private string  $content,
        private ?string $projectId = null,
    ) {
        //
    }
    
    /**
     * タスクが最大数を超えてるか判定する
     *
     * @param  Project $project
     * @return void
     */
    public function exceedsTaskLimit(Project $project)
    {                
        $taskCount = $project
            ->load('tasks')
            ->tasks
            ->count();

        return $taskCount >= self::MAX_TASK_COUNT;
    }

    public function projectId(): string
    {
        if (!Str::isUlid($this->projectId)) {
            throw new Exception('プロジェクトIDが無効です。');
        }

        return $this->projectId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }
}