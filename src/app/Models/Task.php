<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Project;
use App\UseCases\Task\Domain\TaskEntity;

/**
 * @property string $id
 * @property string $project_id
 * @property string $name
 * @property string $content
 * @property bool   $is_complete
 */
class Task extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'project_id',
        'name',
        'content',
        'is_complete'
    ];

    protected $casts = [
        'is_complete' => 'bool'
    ];

    /**
     * projectsRelation
     *
     * @return BelongsTo<Project, Task>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function fromEntity(
        $name,
        $content,
        $isComplete): self
    {
        $this->name        = $name;
        $this->content     = $content;
        $this->is_complete = $isComplete;

        return $this;
    }

    /**
     * 未完了のタスクを取得
     *
     * @param  Builder<Task> $query
     * @param  string $id
     * @return void
     */
    public function scopeIncomplete(Builder $query): void
    {
        $query->where('is_complete', false);
    }

    /**
     * Timestampを除外する
     *
     * @param  Builder<Task> $query
     * @param  string $id
     * @return void
     */
    public function scopeExcludeTimestamps(Builder $query): void
    {
        $query->select([
            'id',
            'project_id',
            'name',
            'content',
            'is_complete'
        ]);
    }
}
