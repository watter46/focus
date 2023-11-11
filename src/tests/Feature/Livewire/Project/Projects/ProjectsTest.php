<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\Projects;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Livewire\Project\Projects\Progress\ProgressType;
use App\Livewire\Project\Projects\Projects;
use App\Livewire\Utils\Label\Enum\LabelType;
use App\Livewire\Utils\Label\Enum\PurposeType;
use App\Livewire\Utils\Label\LabelCommand;
use App\Livewire\Utils\Label\LabelPresenterResolver;
use App\Models\Project;
use App\Models\User;


final class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_未完了のプロジェクトのみ取得しているか()
    {
        $this->actingAs(User::factory()->create());

        Project::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_complete' => false],
                ['is_complete' => true],
            ))
            ->create();
            
        Livewire::test(Projects::class)
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(3, count($projects));

                return true;
            });
    }

    public function test_ラベルがソートできるか()
    {
        $this->actingAs(User::factory()->create());

        Project::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_complete' => false, 'label' => LabelType::Todo],
                ['is_complete' => false, 'label' => LabelType::Fix],
            ))
            ->create();

        $command = new LabelCommand(new LabelPresenterResolver);

        $sortLabelPresenter = $command->execute(PurposeType::sort);
        $label = $sortLabelPresenter->defaultLabel();
            
        $component = Livewire::test(Projects::class)->set('form.label', $label);
            
        // Todoラベルでソートする
        $component
            ->call('sortLabel', 'Todo')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(3, count($projects));

                return true;
            });
    
        // Fixラベルでソートする
        $component
            ->call('sortLabel', 'Fix')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(2, count($projects));

                return true;
            });
    
        // Noneラベルでソートする
        $component
            ->call('sortLabel', 'None')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(0, count($projects));

                return true;
            });
    }

    public function test_進捗がソートできるか()
    {
        $this->actingAs(User::factory()->create());

        Project::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_complete' => false, 'label' => LabelType::Todo],
                ['is_complete' => true, 'label' => LabelType::Fix]
            ))
            ->create();
        
        // completedでソートする
        Livewire::test(Projects::class)
            ->call('sortProgress', 'completed')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(2, count($projects));

                return true;
            });
        
        // allでソートする
        Livewire::test(Projects::class)
            ->call('sortProgress', 'all')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(5, count($projects));

                return true;
            });
        
        // 進捗ではソートしない
        Livewire::test(Projects::class)
            ->call('sortProgress', '')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(3, count($projects));

                return true;
            });
    }

    public function test_進捗の数が常に1追加になるか()
    {
        $this->actingAs(User::factory()->create());

        Project::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_complete' => false],
                ['is_complete' => true],
            ))
            ->create(); 
            
        Livewire::test(Projects::class)
            ->set('form.progress', collect(ProgressType::Completed))
            ->call('sortProgress', ProgressType::Completed->value)
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(2, count($projects));

                return true;
            });
    }
    
    public function test_ラベルと進捗でソートできるか()
    {
        $this->actingAs(User::factory()->create());

        Project::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_complete' => false, 'label' => LabelType::Fix],
                ['is_complete' => true, 'label' => LabelType::Fix]
            ))
            ->create();

        $command = new LabelCommand(new LabelPresenterResolver);

        $sortLabelPresenter = $command->execute(PurposeType::sort);
        $label = $sortLabelPresenter->defaultLabel();
            
        $component = Livewire::test(Projects::class)
                        ->set('form.label', $label);
        
        // label: Fix, progress: ''でソートする
        // label: Fix, progress: completedでソートする
        // label: None, progress: completedでソートする
        $component
            ->call('sortLabel', 'Fix')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(3, count($projects));

                return true;
            })
            ->call('sortProgress', 'completed')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(2, count($projects));

                return true;
            })
            ->call('sortLabel', 'None')
            ->assertViewHas('projects', function ($projects) {
                $this->assertSame(0, count($projects));

                return true;
            });
    }
}