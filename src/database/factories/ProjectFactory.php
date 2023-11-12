<?php declare(strict_types=1);

namespace Database\Factories;

use App\Livewire\Utils\Label\Enum\LabelType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_name' => 'test',
            'label'        => LabelType::Idea,
            'is_complete'  => false
        ];
    }
}