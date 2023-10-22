<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;

final readonly class CreateProjectUseCase
{
    public function __construct()
    {
        //
    }
    
    /**
     * execute
     *
     * @param CreateProjectCommand $command
     * @return Project
     */
    public function execute(CreateProjectCommand $command): Project
    {
        try {
            return DB::transaction(function () use ($command) {
                $project = $command->makeProjectTask();
        
                $project->store();

                return $project;
            });
        } catch (Exception $e) {
            
        }
    }
}