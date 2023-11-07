<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;

use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\Projects\Progress\Progress;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelInterface;
use App\Livewire\Utils\Label\ReadLabelInterface;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\SortProjects\SortProjectsCommand;
use App\UseCases\Project\SortProjects\SortProjectsUseCase;


final class Projects extends Component
{
    use WithPagination;

    public ProjectsForm $form;

    private readonly SortProjectsUseCase $sortProjects;
    private readonly LabelInterface     $sortLabelPresenter;
    private readonly ReadLabelInterface $displayLabelPresenter;

    public function boot(
        SortProjectsUseCase $sortProjects,
        LabelCommand $command
    ) {
        $this->sortProjects = $sortProjects;
        $this->sortLabelPresenter = $command->execute(PurposeType::sort);
        $this->displayLabelPresenter = $command->execute(PurposeType::display);
    }

    public function mount(): void
    {        
        $this->form->LABELS = $this->sortLabelPresenter->labels();
        $this->form->label  = $this->sortLabelPresenter->defaultLabel();
    }
    
    /**
     * Progressの選択数を1以下に保つ
     *
     * @return void
     */
    public function updatedForm(): void
    {
        $this->form->forceProgressLimit();
    }
    
    #[Layout('layouts.app')]
    /**
     * sortLabelPresenterから$this->project(プロパティ)に一度入れてから、renderに渡していたが、
     * ページネーションのほかのページに飛べなかったので、sortLabelPresenterでオプションの有無で
     * Sortか通常の取得かを判定するようにする。
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.project.projects.projects', [
            'projects' => $this->sortProjects->execute(
                new SortProjectsCommand($this->form->options)
            )
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
        $this->redirectRoute('project.detail', $projectId);
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
        $newLabel = $this
                    ->sortLabelPresenter
                    ->change($this->form->label, $selectedLabel);

        $this->form->label = $this->sortLabelPresenter->toViewData($newLabel);
        
        $this->form->setLabel($newLabel);
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
            $newProgress = Progress::get($this->form->options->getProgress(), $progress);

            $this->form->setProgress($newProgress);

        } catch (Exception $e) {
            $this->notify(Message::createErrorMessage($e));
        }
    }
    
    /**
     * 同じラベルか判定する
     *
     * @param  Collection $label
     * @return bool
     */
    private function isSame(Collection $label): bool
    {
        return $label->get('text') === $this->form->label->get('text');
    }
        
    /**
     * ラベルの種類からViewデータを作成する
     *
     * @param  LabelType $label
     * @return Collection
     */
    private function labelData(LabelType $label): Collection
    {
        return $this->displayLabelPresenter->toViewData($label);
    }
}