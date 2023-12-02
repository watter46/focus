<?php declare(strict_types=1);

namespace App\Livewire\Development;

use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

use App\Models\Development as EqDevelopment;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\DevelopmentCommand;
use App\UseCases\Development\CompleteDevelopmentUseCase;
use App\UseCases\Development\FetchDevelopmentUseCase;
use App\UseCases\Development\StartDevelopmentUseCase;
use App\UseCases\Development\ClearDevelopmentUseCase;
use App\UseCases\Development\RepeatDevelopmentUseCase;
use App\UseCases\Development\StopDevelopmentUseCase;


final class Development extends Component
{
    #[Locked]
    public string $projectId;

    #[Locked]
    public EqDevelopment $development;

    private readonly FetchDevelopmentUseCase    $fetchDevelopment;
    private readonly StartDevelopmentUseCase    $startDevelopment;
    private readonly StopDevelopmentUseCase     $stopDevelopment;
    private readonly CompleteDevelopmentUseCase $completeDevelopment;
    private readonly ClearDevelopmentUseCase    $clearDevelopment;
    private readonly RepeatDevelopmentUseCase   $repeatDevelopment;

    public function boot(
        FetchDevelopmentUseCase    $fetchDevelopment,
        StartDevelopmentUseCase    $startDevelopment,
        StopDevelopmentUseCase     $stopDevelopment,
        CompleteDevelopmentUseCase $completeDevelopment,
        ClearDevelopmentUseCase    $clearDevelopment,
        RepeatDevelopmentUseCase   $repeatDevelopment)
    {
        $this->fetchDevelopment    = $fetchDevelopment;
        $this->startDevelopment    = $startDevelopment;
        $this->stopDevelopment     = $stopDevelopment;
        $this->completeDevelopment = $completeDevelopment;
        $this->clearDevelopment    = $clearDevelopment;
        $this->repeatDevelopment   = $repeatDevelopment;
    }

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
        
        $this->setupView($projectId);
    }

    public function hydrate()
    {
        $this->setupView($this->projectId);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.development.development');
    }

    /**
     * viewのデータを取得
     *
     * @param string $projectId
     * @return void
     */
    public function setupView(string $projectId): void
    {
        try {
            $command = DevelopmentCommand::findByProjectId($projectId);
            
            $this->development = $this->fetchDevelopment->execute($command);
            
        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * 開発を始める
     *
     * @param  int   $defaultTime
     * @param  array $selectedIdList
     * @return void
     */
    #[On('timer-started')]
    public function start(int $defaultTime, array $selectedIdList): void
    {
        try {
            if ($this->development->canRestart()) return;
    
            $command = DevelopmentCommand::start(
                    projectId: $this->projectId,
                    defaultTime: $defaultTime,
                    selectedIdList: $selectedIdList
                );
    
            $this->development = $this->startDevelopment->execute($command);
            
            $this->dispatch('development-started', $this->development->id);

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * 開発を止める
     *
     * @param  int $remainingTime
     * @return void
     */
    #[On('timer-stopped')]
    public function stop(int $remainingTime): void
    {
        try {
            // 連打防止で残り時間が変わっていない場合、後続の処理を行わない        
            if ($this->development->isRemainingTimeStatic($remainingTime)) return;
            
            if ($remainingTime <= 0) {
                $this->complete();
                return;
            }

            $command = DevelopmentCommand::stop(
                    developmentId: $this->development->id,
                    remainingTime: $remainingTime
                );

            $this->development = $this->stopDevelopment->execute($command);

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * 開発を完了する
     *
     * @return void
     */
    #[On('timer-completed')]
    public function complete(): void
    {
        try {    
            $command = DevelopmentCommand::findByDevelopmentId($this->development->id);
    
            $this->development = $this->completeDevelopment->execute($command);

            $this->dispatch('on-start-break-time');

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * 開発を終了する
     *
     * @return void
     */
    #[On('timer-cleared')]
    public function clear(): void
    {
        try {
            $command = DevelopmentCommand::findByDevelopmentId($this->development->id);

            $this->development = $this->clearDevelopment->execute($command);

            $this->dispatch('development-finished');
            
            $this->dispatch('initialize');

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * タイマーをリピートする
     *
     * @param  string $projectId
     * @return void
     */
    #[On('break-time-finished')]
    public function repeat(string $projectId): void
    {        
        try {
            $command = DevelopmentCommand::findByProjectId($projectId);

            $this->development = $this->repeatDevelopment->execute($command);
            dd($this->development);
            $this->dispatch(
                'on-repeat-timer',
                defaultTime:    $this->development->default_time,
                selectedIdList: $this->development->selected_id_list
            );

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * タイマーを強制的に止める
     *
     * @param  Message $message
     * @return void
     */
    private function killTimer(Message $message): void
    {
        $this->dispatch('on-kill-timer');

        $this->notify($message);
    }
}