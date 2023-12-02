<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\History;
use App\Models\User;
use App\Livewire\Dashboard\Histories;


class HistoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_レンダリングされるか()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        History::factory(7)
            ->state([
                'user_id' => $user,
                'started_at'   => now(),
                'finished_at'  => now()->addMinutes(30),
            ])
            ->create();

        Livewire::test(Histories::class)
            ->assertStatus(200)
            ->assertViewHas('histories', function ($histories) {
                $this->assertCount(4, $histories);

                return true;
            });
    }
}