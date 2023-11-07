<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail\ProjectName;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Locked;

use App\UseCases\Project\FetchProject\FetchProjectUseCase;
use App\UseCases\Project\CompleteProject\CompleteProjectUseCase;
use App\UseCases\Project\IncompleteProject\IncompleteProjectUseCase;
use App\UseCases\Project\UpdateProjectName\UpdateProjectNameUseCase;
use App\UseCases\Project\ProjectCommand;
use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Utils\Message\Message;


final class ProjectName extends Component
{
    #[Locked]
    public string $projectId;
    
    public ProjectNameForm $form;

    private readonly FetchProjectUseCase      $fetchProject;
    private readonly UpdateProjectNameUseCase $updateProjectName;
    private readonly CompleteProjectUseCase   $completeProject;
    private readonly IncompleteProjectUseCase $incompleteProject;

    public function boot(
        FetchProjectUseCase      $fetchProject,
        UpdateProjectNameUseCase $updateProjectName,
        CompleteProjectUseCase   $completeProject,
        IncompleteProjectUseCase $incompleteProject
    ) {
        $this->fetchProject      = $fetchProject;
        $this->updateProjectName = $updateProjectName;
        $this->completeProject   = $completeProject;
        $this->incompleteProject = $incompleteProject;
    }

    public function mount()
    {
        $command = new ProjectCommand($this->projectId);
        
        $project = $this->fetchProject->execute($command);

        $this->form->setProperty($project);
    }
    
    public function render()
    {
        return view('livewire.project.project-detail.project-name.project-name');
    }

    /**
     * プロジェクト名をアップデートする
     *
     * @return void
     */
    public function update(): void
    {        
        try {
            $this->validate();

            $command = new ProjectCommand(
                projectId: $this->projectId,
                name: $this->form->projectName
            );

            $project = $this->updateProjectName->execute($command);

            $this->form->setProperty($project);
            
            $this->notify(Message::createSavedMessage());
            
            $this->dispatch('close-project-name-input');

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * プロジェクトを完了する
     *
     * @return void
     */
    public function complete(): void
    {
        try {
            $command = new ProjectCommand($this->projectId);

            $this->completeProject->execute($command);

            $this->toProjectsPage();

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * プロジェクトを未完了にする
     *
     * @return void
     */
    public function incomplete(): void
    {
        try {
            $command = new ProjectCommand($this->projectId);
            
            $project = $this->incompleteProject->execute($command);
            
            $this->form->setProperty($project);

            $this->notify(Message::createSavedMessage());

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }

    /**
     * Inputの末尾にフォーカスする
     *
     * @return void
     */
    public function focusEnd(): void
    {
        $this->dispatch('focus-end');
    }

    /**
     * NewProjectに飛ぶ
     *
     * @return void
     */
    public function toNewProjectPage(): void
    {
        $this->redirect(NewProject::class);
    }

    /**
     * プロジェクトページに飛ぶ
     *
     * @return void
     */
    private function toProjectsPage(): void
    {
        $this->redirect(Projects::class);
    }
}