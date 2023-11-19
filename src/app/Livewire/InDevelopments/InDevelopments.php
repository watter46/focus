<?php declare(strict_types=1);

namespace App\Livewire\InDevelopments;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\ReadLabelInterface;
use App\UseCases\Development\FetchProjectByActiveDevelopmentUseCase;

 
final class InDevelopments extends Component
{
    use WithPagination;
    
    private readonly ReadLabelInterface $displayLabelPresenter;
    private readonly FetchProjectByActiveDevelopmentUseCase $fetchProjectByActiveDevelopment;
    
    public function boot(
        LabelCommand $command,
        FetchProjectByActiveDevelopmentUseCase $fetchProjectByActiveDevelopment)
    {
        $this->displayLabelPresenter = $command->execute(PurposeType::display);
        $this->fetchProjectByActiveDevelopment = $fetchProjectByActiveDevelopment;
    }
    
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.in-developments.in-developments', [
            'projects' => $this->fetchProjectByActiveDevelopment->execute()
        ]);
    }
    
    /**
     * Developmentへ移動
     *
     * @param  string $projectId
     * @return void
     */
    public function toDevelopment(string $projectId): void
    {
        $this->redirectRoute('development', [
            'projectId' => $projectId
        ]);
    }

    /**
     * ラベルの種類からViewデータを作成する
     *
     * @param  LabelType $label
     * @return Collection
     */
    private function labelData(LabelType $label): Collection
    {
        return $this->displayLabelPresenter->toViewData($label);
    }
}