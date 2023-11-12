<?php declare(strict_types=1);

namespace App\Livewire\Project\ProjectDetail;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;


final class ProjectDetail extends Component
{
    #[Locked]
    public string $projectId;

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
    }
    
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.project.project-detail.project-detail');
    }
    
    /**
     * DevelopmentPageへ移動する
     *
     * @return void
     */
    public function toDevelopmentPage()
    {
        return redirect("/developments/{$this->projectId}");
    }
}