<?php declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection as EqCollection;

use App\Models\History as EqHistory;
use App\UseCases\History\FetchChartDataUseCase;


final readonly class ChartPresenter
{    
    public function __construct(private FetchChartDataUseCase $fetchChartData)
    {
        //
    }

    public function fetchChartData(): Collection
    {
        $histories = $this->fetchChartData->execute();
        
        $aWeekAgo  = $this->chartData($histories->get('aWeekAgo'));
        $totalTime = $this->toHours($histories->get('totalTime'));
        $weeklyAvg = $this->getWeeklyAvgTime_min($aWeekAgo);
        
        $histories->put('aWeekAgo', $aWeekAgo);
        $histories->put('totalTime', $totalTime);
        $histories->put('weeklyAvg', $weeklyAvg);

        return $histories;
    }

    /**
     * 今日を含めた一週間の平均時間を取得
     *
     * @param  array {string: int} $weeklyData
     * @return int
     */
    public function getWeeklyAvgTime_min(array $weeklyData): int
    {
        $avg_min = round(collect($weeklyData)->average('time_min'));

        return intval($avg_min);
    }

    /**
     * 今日を含めた1週間の開発記録をchartで使えるデータに変換する
     * @param  EqCollection<int, EqHistory> $histories
     * @return array {date: string, time: int}
     */
    private function chartData(EqCollection $histories)
    {
        $oneWeekAgo = now()->subWeek()->addDay();
        $now        = now();

        $oneWeekDates = CarbonPeriod::create($oneWeekAgo, $now)->toArray();

        /**
         * 今日を含めた1週間の日にちを取得する
         * 
         * @var Collection<string　日付, int　時間(初期値なので0)> $dates
         */
        $dates = collect($oneWeekDates)
            ->map(fn(Carbon $date) => $date->toDateString())
            ->mapWithKeys(fn($date) => [$date => 0]);        
                         
        /** 
         * 日付ごとに経過時間を合計する
         * 
         * @var Collection<string　日付, int　時間> $chartData
         */
        $chartData = $histories
            ->groupBy(fn (EqHistory $model) => Carbon::parse($model->finished_at)->toDateString())
            ->map(fn(Collection $models) => $models->sum('elapsed_time'))
            ->sortKeys();

        /**
         * 今日を含めた1週間のchartデータを作成する
         * 
         * @var array {date: string, time_min: int} $result
         */
        $result = $dates
            ->merge($chartData)
            ->mapToGroups(function (int $time_sec, string $date) {
                return [[
                    'date'     => Carbon::parse($date)->format('m/d'),
                    'time_min' => $this->toMinutes($time_sec)
                ]];
            })
            ->first()
            ->toArray();
                  
        return $result;
    }

    /**
     * 秒を時間に変換する
     * 割り切れない場合四捨五入する
     *
     * @param  int $time_sec
     * @return int
     */
    private function toHours(int $time_sec): int
    {
        return (int) round($time_sec / 3600);
    }
    
    /**
     * 秒を分に変換する
     * 割り切れない場合四捨五入する
     *
     * @param  int $time_sec
     * @return int
     */
    private function toMinutes(int $time_sec): int
    {
        return (int) round($time_sec / 60);
    }
}
