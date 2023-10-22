<?php declare(strict_types=1);

namespace App\UseCases\Project;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final readonly class CreateProjectCommand
{
    public function __construct(private Collection $validated)
    {
        //
    }
    
    /**
     * ProjectとTaskのモデルを作成する
     *
     * @return Project
     */
    public function makeProjectTask(): Project
    {
        $form = $this->validated->first();
                
        $project = new Project([
            'user_id'      => Auth::user()->id,
            'project_name' => $form['projectName'],
            'label'        => $form['label'],
            'is_complete'  => false
        ]);

        $task = new Task([
            'name'        => $form['name'],
            'content'     => $form['content'],
            'is_complete' => false
        ]);

        $project->setRelation('tasks', $task);
        
        return $project;
    }
}