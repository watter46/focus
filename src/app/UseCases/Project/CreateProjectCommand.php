<?php declare(strict_types=1);

namespace App\UseCases\Project;

use App\Livewire\Utils\Label\Enum\LabelType;
use App\UseCases\Project\RegisterTask\TaskInProject;

final readonly class CreateProjectCommand
{
    public function __construct(
        private string $projectName,
        private LabelType $label,
        private string $name,
        private string $content 
    ) {
        //
    }

    public function projectName(): string
    {
        return $this->projectName;
    }

    public function label(): LabelType
    {
        return $this->label;
    }

    public function taskInProjectCommand(): TaskInProject
    {
        return new TaskInProject(
            name: $this->name,
            content: $this->content
        );
    }
    
    // /**
    //  * ProjectとTaskのモデルを作成する
    //  *
    //  * @return Project
    //  */
    // public function makeProjectTask(): Project
    // {
    //     $form = $this->validated->first();
                
    //     $project = new Project([
    //         'user_id'      => Auth::user()->id,
    //         'project_name' => $form['projectName'],
    //         'label'        => $form['label'],
    //         'is_complete'  => false
    //     ]);

    //     $task = new Task([
    //         'name'        => $form['name'],
    //         'content'     => $form['content'],
    //         'is_complete' => false
    //     ]);

    //     $project->setRelation('tasks', $task);
        
    //     return $project;
    // }
}