<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use App\UseCases\Development\Domain\DevelopmentEntity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $id
 * @property string $user_id
 * @property string $project_id
 * @property bool   $is_start
 * @property bool   $is_complete
 * @property int    $default_time
 * @property int    $remaining_time
 * @property Carbon $started_at
 * @property Carbon $finished_at
 * @property array  $selected_id_list
 */
final class Development extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'project_id',
        'is_start',
        'is_complete',
        'default_time',
        'remaining_time',
        'started_at',
        'finished_at',
        'selected_id_list'
    ];

    protected $casts = [
        'is_start'    => 'boolean',
        'is_complete' => 'boolean',
        'started_at'  => 'date',
        'finished_at' => 'date',
    ];

    protected function selectedIdList(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => unserialize($value),
            set: fn (array  $value) => serialize($value)
        );
    }
    
    /**
     * 開発をスタートできるか判定する
     *
     * @return bool
     */
    public function canRestart(): bool
    {
        if ($this->is_complete) {
            return false;
        }

        if ($this->remaining_time <= 0) {
            return false;
        }

        if (!$this->is_start) {
            return false;
        }

        return true;
    }

    /**
     * 残り時間が進んでいるか判定
     *
     * @param  int $remainingTime
     * @return bool
     */
    public function isRemainingTimeStatic(int $remainingTime): bool
    {
        return $this->remaining_time === $remainingTime;
    }

    public function toEntity(): DevelopmentEntity
    {
        return (new DevelopmentEntity)->reconstruct($this);
    }

    public function fromEntity(
        bool    $isStart,
        bool    $isComplete,
        int     $defaultTime,
        int     $remainingTime,
        ?Carbon $startedAt,
        ?Carbon $finished,
        array   $selectedIdList): self
    {
        $this->is_start       = $isStart;
        $this->is_complete    = $isComplete;
        $this->default_time   = $defaultTime;
        $this->remaining_time = $remainingTime;
        $this->started_at     = $startedAt;
        $this->finished_at    = $finished;
        $this->selected_id_list = $selectedIdList;
        
        return $this;
    }
    
    /**
     * timerRelation
     *
     * @return HasOne
     */
    public function timer(): HasOne
    {
        return $this->hasOne(Timer::class);
    }

    /**
     * projectRelation
     *
     * @return BelongsTo<Project, Timer>
     */
    public function project(): BelongsTo
    {
        return $this->BelongsTo(Project::class);
    }
    
    /**
     * 未完了の開発を取得する
     *
     * @param  Builder<Development> $query
     * @return void
     */
    public function scopeInDevelopment(Builder $query)
    {
        $query
            ->where('is_start', true)
            ->where('is_complete', false);
    }
}