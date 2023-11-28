<?php declare(strict_types=1);

namespace Database\Factories;

use App\Livewire\Utils\Label\Enum\LabelType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween(
            $startData = '-7 days',
            $endDate   = '-6 hours'
        );

        return [
            'project_name' => 'ProjectName',
            'label'        => LabelType::Idea,
            'started_at'   => Carbon::parse($startedAt),
            'finished_at'  => Carbon::parse($startedAt)->addMinutes(30),
            'elapsed_time' => 30 * 60,
            'completed_task_list' => ['completedTask', 'completedTask2', 'completedTask3']
        ];
    }
}