<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Task;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Project\Projects\Progress\ProgressType;
use App\UseCases\Project\CreateProject\CreateProjectCommand;
use App\UseCases\Project\ProjectCommand;
use Illuminate\Support\Facades\Auth;

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

    public function createProject(CreateProjectCommand $command): self
    {
        $this->user_id      = Auth::user()->id;
        $this->project_name = $command->projectName();
        $this->label        = $command->label();
        $this->is_complete  = false;
        
        return $this;
    }
    
    /**
     * プロジェクト名を更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateProjectName(ProjectCommand $command): self
    {
        $this->project_name = $command->name();
        
        return $this;
    }

    /**
     * ラベルを更新する
     *
     * @param  ProjectCommand $command
     * @return self
     */
    public function updateLabel(ProjectCommand $command): self
    {
        $this->label = $command->label();
        
        return $this;
    }
    
    /**
     * プロジェクトを完了する
     *
     * @return self
     */
    public function complete(): self
    {
        $this->is_complete = true;

        return $this;
    }

    /**
     * プロジェクトを未完了にする
     *
     * @return self
     */
    public function incomplete(): self
    {
        $this->is_complete = false;

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
     * userRelation
     *
     * @return BelongsTo<User, Project>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * tasksRelation
     *
     * @return HasMany<Task>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function incompleteTasks(): HasMany
    {
        return $this
            ->hasMany(Task::class)
            ->where('is_complete', false);
    }
    
    /**
     * withTaskCount
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
}