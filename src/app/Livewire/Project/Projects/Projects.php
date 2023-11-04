<?php declare(strict_types=1);

namespace App\Livewire\Project\Projects;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;

use App\Livewire\Project\NewProject\NewProject;
use App\Livewire\Project\Projects\Progress\Progress;
use App\Livewire\Utils\Label\SortLabelPresenter;
use App\Livewire\Utils\Message\Message;
use App\UseCases\Project\SortProjects\SortProjectsCommand;
use App\UseCases\Project\SortProjects\SortProjectsUseCase;


final class Projects extends Component
{
    use WithPagination;

    /**
     * privateにすると、何の型でも許されるせいなのか、
     * ソートしながらページネーションできなくなるのでpublicにする
     *
     * @var Collection<array{label: string, progress: string}> $options
     */
    public Collection $options;
    public Collection $label;
    public Collection $progress;
    public Collection $LABELS;

    private readonly SortProjectsUseCase $sortProjects;
    private readonly SortLabelPresenter  $presenter;

    public function boot(
        SortProjectsUseCase $sortProjects,
        SortLabelPresenter  $presenter
    ) {
        $this->sortProjects = $sortProjects;
        $this->presenter    = $presenter;
    }

    public function mount(): void
    {
        $this->options  = collect(['label' => '', 'progress' => '']);
        $this->progress = collect();
        $this->LABELS   = $this->presenter->labels();
        $this->label    = $this->presenter->unselected();
    }
    
    /**
     * Progressの選択数を1以下に保つ
     *
     * @return void
     */
    public function updatedProgress(): void
    {
        if ($this->progress->count() > 1) {
            $this->progress->shift();
        }
    }
    
    #[Layout('layouts.app')]
    /**
     * Presenterから$this->project(プロパティ)に一度入れてから、renderに渡していたが、
     * ページネーションのほかのページに飛べなかったので、Presenterでオプションの有無で
     * Sortか通常の取得かを判定するようにする。
     *
     * @return void
     */
    public function render()
    {                
        return view('livewire.project.projects.projects', [
            'projects' => $this->sortProjects->execute(
                new SortProjectsCommand($this->options)
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
        $this->label = $this
                        ->presenter
                        ->change($this->label, $selectedLabel);

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
            $this->notify(Message::createErrorMessage($e));
        }
    }
}