<?php declare(strict_types=1);

namespace App\Livewire\Development\BreakTime;

use App\UseCases\Setting\FetchSettingUseCase;
use Livewire\Component;
use Livewire\Attributes\Locked;


final class BreakTime extends Component
{
    #[Locked]
    public string $projectId;

    public int $breakTime; 
    
    private readonly FetchSettingUseCase $fetchSetting;
    
    public function boot(FetchSettingUseCase $fetchSetting)
    {
        $this->fetchSetting = $fetchSetting;
    }

    public function mount()
    {
        $this->breakTime = $this->fetchSetting->execute()->break_time;
    }
    
    public function render()
    {
        return view('livewire.development.break-time.break-time');
    }
    
    /**
     * 開発をリピートする
     *
     * @return void
     */
    public function repeat(): void
    {
        $this->dispatch('break-time-finished', $this->projectId);
    }
    
    /**
     * 開発をはじめからする
     *
     * @return void
     */
    public function fresh(): void
    {
        redirect("/developments/{$this->projectId}");
    }
}