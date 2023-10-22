<?php declare(strict_types=1);

namespace App\Livewire\Project\NewProject;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Livewire\Utils\Label\Label;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\CreateProjectCommand;
use App\Livewire\Project\NewProject\NewProjectFormableTrait;


final class NewProject extends Component
{
    use NewProjectFormableTrait;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.project.new-project.new-project');
    }
    
    /**
     * ラベルを更新する
     *
     * @param  string $selected
     * @return void
     */
    public function updatedLabel(string $selected): void
    {        
        try {
            $this->label = Label::Select()->change($this->label->get('text'), $selected);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e->getMessage()));
        }
    }

    /**
     * 新規プロジェクトを作成する
     *
     * @return void
     */
    public function create(): void
    {
        $validated = collect($this->validate());
        
        try {
            $command = new CreateProjectCommand($validated);
            
            $project = $this->createProject->execute($command);

            $this->notify(Message::createSavedMessage());

            $this->redirectRoute('projectDetail.project-detail', [
                'projectId' => $project->id
            ]);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e->getMessage()));
        }
    }
}