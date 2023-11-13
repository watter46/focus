<?php declare(strict_types=1);

namespace App\Livewire\Setting;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

use App\Livewire\Utils\Message\Message;
use App\UseCases\Setting\SettingCommand;
use App\UseCases\Setting\Fetch\FetchSettingUseCase;
use App\UseCases\Setting\Update\UpdateSettingUseCase;


final class Setting extends Component
{
    #[Rule('required|int')]
    public int $defaultTime;

    #[Rule('required|int')]
    public int $breakTime;

    private readonly FetchSettingUseCase  $fetchSetting;
    private readonly UpdateSettingUseCase $updateSetting;

    public function boot(
        FetchSettingUseCase  $fetchSetting,
        UpdateSettingUseCase $updateSetting
    ) {
        $this->fetchSetting  = $fetchSetting;
        $this->updateSetting = $updateSetting;
    }

    public function mount()
    {
        $setting = $this->fetchSetting->execute();
        
        $this->set($setting);
    }
    
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.setting');
    }

    private function set($setting): void
    {
        $this->defaultTime = $setting->default_time;
        $this->breakTime   = $setting->break_time;
    }
    
    /**
     * 設定をアップデートする
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();

        try {
            $command = new SettingCommand($this->defaultTime, $this->breakTime);
        
            $setting = $this->updateSetting->execute($command);
            
            $this->set($setting);
                        
            $this->notify(Message::createSavedMessage());

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
}