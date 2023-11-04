<?php declare(strict_types=1);

namespace App\UseCases\Project\UpdateLabel;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


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

            $project->updateLabel($command);
            
            DB::transaction(function () use ($project) {                
                $project->save();
            });

            return $project;

        } catch (Exception $e) {
            throw $e;
        }
    }
}