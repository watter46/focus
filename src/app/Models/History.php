<?php declare(strict_types=1);

namespace App\Models;

use App\Livewire\Utils\Label\Enum\LabelType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * @property string    $id
 * @property string    $user_id
 * @property string    $project_name
 * @property LabelType $label
 * @property string    $started_at
 * @property string    $finished_at
 * @property int       $elapsed_time
 * @property array     $completed_task_list
 */
class History extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'project_name',
        'label',
        'started_at',
        'finished_at',
        'elapsed_time',
        'completed_task_list'
    ];

    protected $casts = [
        'label' => LabelType::class
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::user()->id);
        });
    }

    public function scopeTotalElapsedTime(): int
    {
        return (int) $this->sum('elapsed_time');
    }

    public function toMin(): float
    {   
        return round($this->elapsed_time / 60, 1);
    }
    
    
    /**
     * 完了しているタスク名のミューテタ
     *
     * @return Attribute
     */
    protected function completedTaskList(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => unserialize($value),
            set: fn (array  $value) => serialize($value)
        );
    }

    /**
     * ユーザーを取得する
     *
     * @return BelongsTo<User, History>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}