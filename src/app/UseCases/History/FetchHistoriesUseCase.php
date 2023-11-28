<?php declare(strict_types=1);

namespace App\UseCases\History;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\History as EqHistory;


final readonly class FetchHistoriesUseCase
{
    const MAX_PER_PAGE = 4;
    
    public function __construct()
    {
        //
    }

    public function execute(): LengthAwarePaginator
    {
        try {
            return EqHistory::latest()->paginate(self::MAX_PER_PAGE);

        } catch (Exception $e) {
            throw $e;
        }
    }
}