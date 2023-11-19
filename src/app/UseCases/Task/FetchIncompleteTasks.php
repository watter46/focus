<?php declare(strict_types=1);

namespace App\UseCases\Task;

use Exception;
use Illuminate\Support\Collection;

use App\Models\Project;


final readonly class FetchIncompleteTasks
{
    public function __construct()
    {
        //
    }

    public function execute(string $projectId): Collection
    {
        try {
            return Project::with('incompleteTasks')
                        ->findOrFail($projectId)
                        ->incompleteTasks;
            
        } catch (Exception $e) {
            throw $e;
        }
    }
}