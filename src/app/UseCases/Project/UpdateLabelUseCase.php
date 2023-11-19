<?php declare(strict_types=1);

namespace App\UseCases\Project;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\Domain\ProjectCommand;


final readonly class UpdateLabelUseCase
{
    public function __construct()
    {
        //
    }
    
    /**
     * ラベルをアップデートする
     *
     * @param  ProjectCommand $command
     * @return Project
     */
    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());
            
            $updated = $project
                        ->toEntity()
                        ->updateLabel($command)
                        ->toModel();
                        
            DB::transaction(function () use ($updated) {                
                $updated->save();
            });

            return $updated;

        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }
}