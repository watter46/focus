<?php declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Livewire\Dashboard\ChartPresenter;


class Chart extends Component
{
    public array $data;
    public int   $totalTime;
    public int   $weeklyAvg;

    private readonly ChartPresenter $presenter;

    public function boot(ChartPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    public function mount()
    {
        $histories = $this->presenter->fetchChartData();
        
        $this->data      = $histories->get('aWeekAgo');
        $this->totalTime = $histories->get('totalTime');
        $this->weeklyAvg = $histories->get('weeklyAvg');
    }
    
    public function render()
    {
        return view('livewire.dashboard.chart');
    }
}
