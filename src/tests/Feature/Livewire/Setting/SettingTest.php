<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\Projects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\User;
use App\Constants\BreakTimeConstants;
use App\Constants\DefaultTimeConstants;
use App\Livewire\Setting\Setting;
use App\Livewire\Utils\Message\Message;
use App\Models\Setting as EqSetting;


final class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_DBにデータがないとき初期値が設定されるか()
    {
        $this->actingAs(User::factory()->create());

        $defaultTime = DefaultTimeConstants::VALUE_min;
        $breakTime   = BreakTimeConstants::VALUE_min;
            
        Livewire::test(Setting::class)
            ->assertSet('defaultTime', $defaultTime)
            ->assertSet('breakTime', $breakTime);
    }

    public function test_DBのデータが設定されるか()
    {
        $this->actingAs(User::factory()->create());

        $setting = EqSetting::factory()->create();

        Livewire::test(Setting::class)
            ->assertSet('defaultTime', $setting->default_time)
            ->assertSet('breakTime', $setting->break_time);
    }

    public function test_更新された値が設定されるか()
    {
        $this->actingAs(User::factory()->create());

        $setting = EqSetting::factory()->create();

        $rendered = Livewire::test(Setting::class)
            ->assertSet('defaultTime', $setting->default_time)
            ->assertSet('breakTime', $setting->break_time);

        $rendered
            ->set('defaultTime', 30)
            ->set('breakTime', 10)
            ->call('update')
            ->assertSet('defaultTime', 30)
            ->assertSet('breakTime', 10)
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}