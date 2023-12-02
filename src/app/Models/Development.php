<?php declare(strict_types=1);

namespace App\Models;

use App\Events\HistoryCreated;
use App\UseCases\Development\Domain\Development as DevelopmentEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

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
 * @property string $started_at
 * @property string $finished_at
 * @property array  $selected_id_list
 */
final class Development extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
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
        'is_complete' => 'boolean'
    ];

    protected function selectedIdList(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => unserialize($value),
            set: fn (array  $value) => serialize($value)
        );
    }
    
    /**
     * 開発を終了して、開発情報を作成する
     *
     * @param  DevelopmentEntity $development
     * @return void
     */
    public function finish(DevelopmentEntity $development): void
    {
        $this->save();

        HistoryCreated::dispatch($development);
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