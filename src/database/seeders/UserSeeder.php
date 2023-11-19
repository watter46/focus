<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Development;
use App\Models\Project;
use App\Models\Task;
use App\Models\Timer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(
                Project::factory(5)
                    ->has(Task::factory(3))
            )
            ->create();
    }
}
