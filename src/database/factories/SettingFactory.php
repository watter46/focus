<?php declare(strict_types=1);

namespace Database\Factories;

use App\Constants\BreakTimeConstants;
use App\Constants\DefaultTimeConstants;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => Auth::user()->id,
            'default_time' => DefaultTimeConstants::VALUE_min,
            'break_time'   => BreakTimeConstants::VALUE_min
        ];
    }
}
