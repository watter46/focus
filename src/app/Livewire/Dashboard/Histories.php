<?php declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\ReadLabelInterface;
use App\UseCases\History\FetchHistoriesUseCase;


class Histories extends Component
{
    use WithPagination;

    private readonly FetchHistoriesUseCase $fetchHistories;
    private readonly ReadLabelInterface    $label;
    
    public function boot(
        FetchHistoriesUseCase $fetchHistories,
        LabelCommand          $command)
    {
        $this->fetchHistories = $fetchHistories;
        $this->label          = $command->execute(PurposeType::display);
    }
        
    /**
     * ページネーションをするため、fetchHistoriesの戻り値はLengthAwarePaginatorにしている
     * publicプロパティにLengthAwarePaginator型の値を入れるとLivewireの仕様上、
     * エラーが起こるためrenderに渡すことで回避する
     *
     * @return void
     */
    public function render()
    {   
        return view('livewire.dashboard.histories', [
            'histories' => $this->fetchHistories->execute()
        ]);
    }

    private function label(LabelType $label): Collection
    {
        return $this->label->toViewData($label);
    }
}