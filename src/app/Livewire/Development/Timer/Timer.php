<?php declare(strict_types=1);

namespace App\Livewire\Development\Timer;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

use App\Livewire\Development\Timer\Status;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\FetchDevelopmentUseCase;


final class Timer extends Component
{
    #[Locked]
    public string $projectId;

    public bool $isStart;

    public int   $defaultTime;
    public int   $remainingTime;
    public array $selectedIdList;

    public bool $muteStart;
    public bool $muteClear;
    public bool $muteTimeSet;

    private readonly Status $status;
    private readonly FetchDevelopmentUseCase $fetchDevelopment;

    public function boot(
        status $status,
        FetchDevelopmentUseCase $fetchDevelopment,
    ) {
        $this->status = $status;
        $this->fetchDevelopment = $fetchDevelopment;
    }

    public function mount()
    {        
        if (!$this->isStart) {
            $this->initialize();
            return;
        }

        $this->status->toPaused();
    }

    public function hydrate()
    {
        if ($this->selectedIdList) {
            $this->status->toReady();
        }
    }
    
    public function render()
    {
        $this->muteButtons();

        return view('livewire.development.timer.timer');
    }
    
    /**
     * タイマーをStartする
     *
     * @return void
     */
    public function start(): void
    {
        $this->status->toRunning();

        $this->isStart = true;
        
        $this->dispatch(
            'timer-started',
            defaultTime:    $this->defaultTime,
            selectedIdList: $this->selectedIdList
        );
    }
    
    /**
     * タイマーをStopする
     *
     * @param  int $remainingTime_sec
     * @return void
     */
    public function stop(int $remainingTime_sec): void
    {
        $this->status->toPaused();

        $this->dispatch('timer-stopped', $remainingTime_sec);
    }
    
    /**
     * タイマーをClearする
     *
     * @return void
     */
    public function clear(): void
    {        
        $this->dispatch('timer-cleared');
    }
    
    /**
     * タイマーをリピートする
     *
     * @param  int   $defaultTime
     * @param  array $selectedIdList
     * @return void
     */
    #[On('on-repeat-timer')]
    public function repeat(int $defaultTime, array $selectedIdList): void
    {        
        $this->setTime($defaultTime);

        $this->setSelectedIdList($selectedIdList);

        $this->isStart = true;

        $this->dispatch('on-reset-timer');
    }

    /**
     * タイマーを強制的に止める
     *
     * @return void
     */
    #[On('on-kill-timer')]
    public function kill(): void
    {
        $this->dispatch('timer-killed');

        $this->initialize();
    }

    /**
     * タイマーを初期化する
     *
     * @return void
     */
    #[On('initialize')]
    public function initialize(): void
    {
        $this->fetchDevelopment();

        $this->status->toDisabled();

        $this->isStart = false;

        $this->selectedIdList = [];

        $this->dispatch('on-reset-timer');
    }

    /**
     * 時間を設定
     *
     * @param  int $defaultTime_sec
     * @return void
     */
    public function setTime(int $defaultTime_sec): void
    {
        $this->defaultTime   = $defaultTime_sec;
        $this->remainingTime = $defaultTime_sec;
    }
    
    /**
     * 選択されたタスクIDを設定する
     *
     * @param  array $selectedIds
     * @return void
     */
    #[On('setSelectedIdList')]
    public function setSelectedIdList(array $selectedIdList): void
    {        
        $this->selectedIdList = $selectedIdList;
        
        if (!$this->selectedIdList) {
            $this->status->toDisabled();
            return;
        };

        $this->status->toReady();
    }

    private function fetchDevelopment(): void
    {
        $command = DevelopmentCommand::findByProjectId($this->projectId);
        
        $development = $this->fetchDevelopment->execute($command);

        $this->defaultTime   = $development->default_time;
        $this->remainingTime = $development->remaining_time;
    }

    private function muteButtons(): void
    {
        $this->muteStart   = $this->status->isMuteStartBtn();
        $this->muteClear   = $this->status->isMuteClearBtn();
        $this->muteTimeSet = $this->status->isMuteTimeSetterBtn($this->isStart);
    }
}