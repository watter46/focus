<?php declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Illuminate\Pagination\LengthAwarePaginator;

use App\Livewire\Utils\Label\Label;
use App\Models\History as EqHistory;
use App\UseCases\History\FetchHistoriesUseCase;


final class HistoriesPresenter
{    
    public function __construct(private readonly FetchHistoriesUseCase $fetchHistories)
    {
        //
    }
    
    /**
     * 今日を含めた1週間の開発記録を取得
     *
     * @return LengthAwarePaginator
     */
    public function fetchHistories(): LengthAwarePaginator
    {
        $histories = $this->fetchHistories
                          ->execute()
                          ->historyList();
        
        $histories->getCollection()
                  ->transform(function (EqHistory $model) {
                        $model->elapsed_time = $this->toMinutes($model->elapsed_time);
                        $model->label        = Label::Display()->of($model->label);

                        return $model;
                    });
        
        return $histories;
    }
    
    /**
     * 秒を分に変換する
     * 割り切れない場合、少数第一位まで四捨五入する
     *
     * @param  int $time_sec
     * @return float
     */
    private function toMinutes(int $time_sec): float
    {
        return round($time_sec / 60, 1);
    }
}
