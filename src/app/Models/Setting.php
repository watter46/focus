<?php declare(strict_types=1);

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


final class Setting extends Model
{
    use HasFactory;
    use HasUlids;

    const DEFAULT_TIME_min = 30; 
    const BREAK_TIME_min   = 10;

    public $incrementing = false;
    public $timestamps   = false;

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

    public function createSetting(): self
    {
        $this->default_time = self::DEFAULT_TIME_min;
        $this->break_time   = self::BREAK_TIME_min;
        
        return $this;
    }

    public function updateSetting(int $defaultTime, int $breakTime): self
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