<?php declare(strict_types=1);

namespace App\Livewire\Development;

use App\Livewire\Development\Timer\Status;
use App\Livewire\Development\Timer\Timer;
use Exception;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

use App\Models\Development as EqDevelopment;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Development\Domain\DevelopmentCommand;
use App\UseCases\Development\CompleteDevelopmentUseCase;
use App\UseCases\Development\FetchDevelopmentUseCase;
use App\UseCases\Development\StartDevelopmentUseCase;
use App\UseCases\Development\FinishDevelopmentUseCase;
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
    private readonly FinishDevelopmentUseCase   $finishDevelopment;
    private readonly RepeatDevelopmentUseCase   $repeatDevelopment;

    public function boot(
        FetchDevelopmentUseCase    $fetchDevelopment,
        StartDevelopmentUseCase    $startDevelopment,
        StopDevelopmentUseCase     $stopDevelopment,
        CompleteDevelopmentUseCase $completeDevelopment,
        FinishDevelopmentUseCase   $finishDevelopment,
        RepeatDevelopmentUseCase   $repeatDevelopment,
        Status $status)
    {
        $this->fetchDevelopment    = $fetchDevelopment;
        $this->startDevelopment    = $startDevelopment;
        $this->stopDevelopment     = $stopDevelopment;
        $this->completeDevelopment = $completeDevelopment;
        $this->finishDevelopment   = $finishDevelopment;
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
    #[On('setupView')]
    public function setupView(string $projectId): void
    {
        try {
            $command = new DevelopmentCommand(projectId: $projectId);
            
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
    #[On('start-development')]
    public function start(int $defaultTime, array $selectedIdList): void
    {
        try {
            if ($this->development->canRestart()) return;
    
            $command = new DevelopmentCommand(
                projectId: $this->projectId,
                defaultTime: $defaultTime,
                remainingTime: $defaultTime,
                selectedIdList: $selectedIdList
            );
    
            $this->development = $this->startDevelopment->execute($command);

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
    #[On('stop-development')]
    public function stop(int $remainingTime): void
    {
        try {
            // 連打防止で残り時間が変わっていない場合、後続の処理を行わない        
            if ($this->development->isRemainingTimeStatic($remainingTime)) return;
            
            if ($remainingTime <= 0) {
                $this->complete();
                return;
            }

            $command = new DevelopmentCommand(
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
    #[On('complete-development')]
    public function complete(): void
    {
        try {
            if (!$this->development->id) {
                $this->dispatch('initialize');
                return;
            };
    
            $command = new DevelopmentCommand(developmentId: $this->development->id);
    
            $this->completeDevelopment->execute($command);
    
            $this->dispatch('start-break-time');

        } catch (Exception $e) {
            $this->killTimer(Message::createErrorMessage($e));
        }
    }

    /**
     * 開発を終了する
     *
     * @return void
     */
    #[On('finish-development')]
    public function clear(): void
    {
        try {
            $this->setupView($this->projectId);

            $command = new DevelopmentCommand(developmentId: $this->development->id);

            $this->finishDevelopment->execute($command);

            $this->setupView($this->projectId);

            $this->dispatch('initialize')->to(Timer::class);

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
    #[On('repeat')]
    public function repeat(string $projectId): void
    {        
        try {
            $command = new DevelopmentCommand(projectId: $projectId);

            $this->development = $this->repeatDevelopment->execute($command);
            
            $this->dispatch(
                'repeat-timer',
                $this->development->default_time,
                $this->development->selected_id_list
            )->to(Timer::class);

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
        $this->dispatch('kill-timer');

        $this->notify($message);
    }
}