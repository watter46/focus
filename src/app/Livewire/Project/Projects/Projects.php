<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\Projects\Progress\Progress;
use App\Livewire\Utils\Label\Label;
use App\Livewire\Utils\Message\Message;
use App\Livewire\Project\Projects\ProjectsFormableTrait;


final class Projects extends Component
{
    use WithPagination;
    use ProjectsFormableTrait;
    
    /**
     * Presenterから$this->project(プロパティ)に一度入れてから、renderに渡していたが、
     * ページネーションのほかのページに飛べなかったので、Presenterでオプションの有無で
     * Sortか通常の取得かを判定するようにする。
     *
     * @return void
     */
    #[Layout('layouts.app')]
    public function render()
    {        
        return view('livewire.project.projects.projects', [
            'projects' => $this->sortProjects->execute($this->options)
        ]);
    }
    
    /**
     * プロジェクト詳細ページへ移動する
     *
     * @param  string $projectId
     * @return void
     */
    public function toProjectDetailPage(string $projectId): void
    {
        $this->redirectRoute('projectDetail.project-detail', $projectId);
    }

    /**
     * NewProjectPageへ移動する
     *
     * @return void
     */
    public function toNewProjectPage(): void
    {
        $this->redirect(NewProject::class);
    }  

    /**
     * 選択したラベルでソートする
     *
     * @param  string $selectedLabel
     * @return void
     */
    public function sortLabel(string $selectedLabel): void
    {
        $this->label = Label::Sort()->change($this->label->get('text'), $selectedLabel);

        $this->options->put('label', $this->label->get('text'));
    }

    /**
     * ソートオプションをセットする
     *
     * @param  string $progress
     * @return void
     */
    public function sortProgress(string $progress): void
    {
        try {
            $newProgress = Progress::get($this->options->get('progress'), $progress);

            $this->options->put('progress', $newProgress->value);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e->getMessage()));
        }
    }
}