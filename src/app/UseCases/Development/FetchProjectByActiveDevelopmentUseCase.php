<?php declare(strict_types=1);

namespace App\UseCases\Development;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Project;


final readonly class FetchProjectByActiveDevelopmentUseCase
{
    public function __construct()
    {
        //
    }
    
    /**
     * 開発途中のプロジェクトを取得する
     *
     * @return LengthAwarePaginator
     */
    public function execute(): LengthAwarePaginator
    {
        try {
            return Project::query()
                        ->withCount('tasks')
                        ->activeDevelopments()
                        ->paginate(5);

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {
            throw $e;
        }
    }
}