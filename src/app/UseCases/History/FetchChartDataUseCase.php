<?php declare(strict_types=1);

namespace App\UseCases\History;

use Exception;
use Illuminate\Support\Collection;

use App\Models\History as EqHistory;


final readonly class FetchChartDataUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(): Collection
    {
        try {
            $history = new Collection();
            
            $history
                ->put('totalTime', EqHistory::totalElapsedTime())
                ->put('aWeekAgo',                    
                    EqHistory::select(['elapsed_time', 'finished_at'])
                        ->where('finished_at', '>', now()->subWeek()->addDay())
                        ->get()
                );
            
            return $history;

        } catch (Exception $e) {
            throw $e;
        }
    }
}