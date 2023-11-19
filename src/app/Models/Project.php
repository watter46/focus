<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use App\Models\Development;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Project\Projects\Progress\ProgressType;
use App\UseCases\Project\Domain\ProjectEntity;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $user_id
 * @property string $project_name
 * @property LabelType $label
 * @property bool $is_complete
 */
final class Project extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'project_name',
        'label',
        'is_complete'
    ];

    protected $casts = [
        'is_complete' => 'boolean',
        'label'       => LabelType::class
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::user()->id);
        });
    }
    
    /**
     * エンティティに変換する
     *
     * @return ProjectEntity
     */
    public function toEntity(): ProjectEntity
    {
        return (new ProjectEntity)->reconstruct($this);
    }
    
    /**
     * モデルに変換する
     *
     * @return self
     */
    public function fromEntity(
        string $projectName,
        LabelType $label,
        bool $isComplete): self
    {
        $this->user_id      = Auth::user()->id;
        $this->project_name = $projectName;
        $this->label        = $label;
        $this->is_complete  = $isComplete;
        
        return $this;
    }

    /**
     * ラベルでソート
     *
     * @param  Builder<Project> $query
     * @param  LabelType $label
     * @return void
     */
    public function scopeLabelIs(Builder $query, LabelType $label): void
    {
        if ($label === LabelType::Unselected) return;

        $query->where('label', $label);
    }

    /**
     * 完了したプロジェクトをソート
     *
     * @param  Builder<Project> $query
     * @param  ProgressType $progress
     * @return void
     */
    public function scopeProgressIs(Builder $query, ProgressType $progress): void
    {
        match($progress) {
            ProgressType::All => $query,
            ProgressType::Completed => $query->where('is_complete', true),
            ProgressType::Unselected => $query->where('is_complete', false)
        };
    }

    /**
     * ユーザーを取得する
     *
     * @return BelongsTo<User, Project>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 開発を取得する
     *
     * @return HasMany
     */
    public function developments(): HasMany
    {
        return $this->hasMany(Development::class);
    }
    
    /**
     * 最新の開発を取得する
     *
     * @return HasOne
     */
    public function latestDevelopment(): HasOne
    {
        return $this
                ->hasOne(Development::class)
                ->latestOfMany();
    }

    /**
     * タスクを全て取得する
     *
     * @return HasMany<Task>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    /**
     * 未完了のタスクをすべて取得する
     *
     * @return HasMany
     */
    public function incompleteTasks(): HasMany
    {
        return $this
                ->hasMany(Task::class)
                ->where('is_complete', false);
    }

    /**
     * 開発できる状態か判定する
     *
     * @return bool
     */
    public function canDevelop(): bool
    {
        if (!$this->latestDevelopment) {
            return false;
        }
        
        if ($this->latestDevelopment->is_complete) {
            return false;
        }

        return true;
    }
    
    /**
     * タスク数と未完了のタスク数を取得する
     *
     * @param  Builder<Project> $query
     * @return void
     */
    public function scopeTasksCount(Builder $query)
    {
        $query->withCount([
            'tasks',
            'tasks as incomplete_tasks_count' => function (Builder $query) {
                $query->where('is_complete', false);
            }
        ]);
    }

    /**
     * 開発途中のプロジェクトを取得する
     *
     * @param  Builder<Project> $query
     * @return void
     */
    public function scopeActiveDevelopments(Builder $query)
    {
        $query
            ->whereHas('developments', function ($query) {
                $query
                    ->where('is_start', true)
                    ->where('is_complete', false);
            });
    }
}