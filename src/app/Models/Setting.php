<?php declare(strict_types=1);

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\UseCases\Setting\SettingEntity;


/**
 * @property int $default_time
 * @property int $break_time
 */
final class Setting extends Model
{
    use HasFactory;
    use HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'default_time',
        'break_time'
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::user()->id);
        });
    }

    public function toEntity(): SettingEntity
    {
        return (new SettingEntity)->reconstruct($this);
    }

    public function fromEntity(int $defaultTime, int $breakTime): self
    {
        $this->user_id      = Auth::user()->id;
        $this->default_time = $defaultTime;
        $this->break_time   = $breakTime;

        return $this;
    }

    /**
     * userRelation
     *
     * @return BelongsTo<User, Setting>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}