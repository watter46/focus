<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\User;
use App\Livewire\Dashboard\Dashboard;


class DashboardTest extends TestCase
{
    public function test_レンダリングされるか()
    {        
        $this->actingAs(User::factory()->create());

        Livewire::test(Dashboard::class)
            ->assertStatus(200);
    }
}
