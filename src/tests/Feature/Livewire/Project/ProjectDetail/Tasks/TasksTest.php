<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail\Tasks;

use Exception;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Livewire\Project\ProjectDetail\Tasks\Tasks;
use App\Livewire\Utils\Message\Message;


class TasksTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        Livewire::test(Tasks::class, ['projectId' => $project->id])
            ->assertSeeLivewire(Tasks::class);
    }

    public function test_レンダリングしたら未完了のタスクのみ取得しているか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(Task::factory(6)
                            ->state(new Sequence(
                                ['is_complete' => false],
                                ['is_complete' => true],
                            ))
                        )
                        ->create();

        Livewire::test(Tasks::class, ['projectId' => $project->id])
            ->assertSet('isShowAll', false)
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(3, count($project->incompleteTasks));

                return true;
            });
    }

    public function test_dispatchで全てのタスクを取得できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(Task::factory(6)
                            ->state(new Sequence(
                                ['is_complete' => false],
                                ['is_complete' => true],
                            ))
                        )
                        ->create();

        Livewire::test(Tasks::class, ['projectId' => $project->id])
            ->dispatch('fetch-project-tasks')
            ->dispatch('refetch')
            ->assertSet('isShowAll', true)
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(6, count($project->tasks));

                return true;
            });
    }

    public function test_タスクを追加できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(Task::factory(6)
                            ->state(new Sequence(
                                ['is_complete' => false],
                                ['is_complete' => true],
                            ))
                        )
                        ->create();

        // レンダリングされるか
        $rendered = Livewire::test(Tasks::class, ['projectId' => $project->id])
            ->set([
                'name'    => 'name test',
                'content' => 'content test'
            ])
            ->assertSet('isShowAll', false)
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(3, count($project->tasks));

                return true;
            });
        
        // notifyイベントが発行されるか
        $notifyEvent = $rendered
            ->dispatch('add')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
        
        // refetchイベントが発行されるか
        $refetchEvent = $notifyEvent
            ->assertDispatched('refetch');
        
        // nameとcontentのフィールドが空になるか
        $fieldReset = $refetchEvent
            ->assertSet('name', null)
            ->assertSet('content', null);
        
        // タスクが追加されるか
        $fieldReset
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(4, count($project->tasks));

                return true;
            });
    }

    public function test_全てのタスクを取得している状態でタスクを追加できるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(Task::factory(6)
                            ->state(new Sequence(
                                ['is_complete' => false],
                                ['is_complete' => true],
                            ))
                        )
                        ->create();

        // レンダリングされるか
        $rendered = Livewire::test(Tasks::class, ['projectId' => $project->id]);
        
        // 全てのタスクを取得できるか
        $dispatchedAllTask = $rendered
            ->dispatch('fetch-project-tasks')
            ->dispatch('refetch')
            ->assertSet('isShowAll', true)
            ->set([
                'name'    => 'name test',
                'content' => 'content test'
            ])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(6, count($project->tasks));

                return true;
            });

        // タスクを追加できるか
        $dispatchedAllTask
            ->call('add')
            ->assertSet('isShowAll', true)
            ->assertViewHas('project', function (Project $project) {
                $this->assertSame(7, count($project->tasks));

                return true;
            });
    }

    public function test_タスク数が11になったらエラーを通知するか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(Task::factory(10)
                            ->state(new Sequence(
                                ['is_complete' => false],
                                ['is_complete' => true],
                            ))
                        )
                        ->create();

        // レンダリングされるか
        $rendered = Livewire::test(Tasks::class, ['projectId' => $project->id]);

        $e = new Exception("タスクの最大数は10です。");
        
        $rendered
            ->set([
                'name'    => 'name test',
                'content' => 'content test'
            ])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->call('add')
            ->assertDispatched('notify', message: Message::createErrorMessage($e)->toArray());
    }
}