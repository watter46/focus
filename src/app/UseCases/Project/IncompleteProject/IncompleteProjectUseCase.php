<?php declare(strict_types=1);

namespace App\UseCases\Project\IncompleteProject;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Project;
use App\UseCases\Project\ProjectCommand;


final readonly class IncompleteProjectUseCase
{
    public function __construct()
    {
        //
    }

    public function execute(ProjectCommand $command): Project
    {
        try {
            /** @var Project $project */
            $project = Project::findOrFail($command->projectId());

            $project->incomplete();
            
            DB::transaction(function () use ($project) {
                $project->save();
            });

            return $project;

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('プロジェクトが見つかりませんでした。');

        } catch (Exception $e) {

        }
    }
}