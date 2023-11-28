<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\History;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(3)
            ->has(
                Project::factory(3)
                    ->has(Task::factory(3))
            )
            ->has(
                History::factory(10)
            )
            ->create();
    }
}
