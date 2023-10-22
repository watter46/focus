<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Task;

/**
 * @property string $id
 * @property string $user_id
 * @property string $project_name
 * @property string $label
 * @property bool   $is_complete
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
        'is_complete' => 'boolean'
    ];
    
    /**
     * Projectとタスクを保存する
     *
     * @return self
     */
    public function store(): self
    {
        $this->save();
        $this->tasks()->save($this->tasks);

        return $this;
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
}
