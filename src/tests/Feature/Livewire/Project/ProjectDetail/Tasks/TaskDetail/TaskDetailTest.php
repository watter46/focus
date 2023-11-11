<?php declare(strict_types=1);

namespace Tests\Feature\Livewire\Project\ProjectDetail\Tasks\TaskDetail;


use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Livewire\Project\ProjectDetail\Tasks\TaskDetail\TaskDetail;
use App\Livewire\Utils\Message\Message;


class TaskDetailTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_レンダリングされるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => 'content test',
                                'is_complete' => false
                            ])))
                        ->create();

        $task = $project->incompleteTasks()->first();

        Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertSet('isComplete', false)
            ->assertSeeLivewire(TaskDetail::class);
    }

    public function test_タスクを完了してdispatchできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => 'content test',
                                'is_complete' => false
                            ])))
                        ->create();

        $task = $project->incompleteTasks()->first();

        // 初期値を設定できるか
        $rendered = Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertSet('isComplete', false)
            ->assertSeeLivewire(TaskDetail::class);
        
        // dispatchできるか
        $rendered
            ->call('complete')
            ->assertDispatched('refetch')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }

    public function test_タスクを未完了にしてdispatchできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => 'content test',
                                'is_complete' => true
                            ])))
                        ->create();

        $task = $project->tasks()->first();

        // 初期値を設定できるか
        $rendered = Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertSet('isComplete', true)
            ->assertSeeLivewire(TaskDetail::class);
        
        // dispatchできるか
        $rendered
            ->call('incomplete')
            ->assertDispatched('refetch')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }

    public function test_タスク名、タスク内容を更新してdispatchできるか()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => 'content test',
                                'is_complete' => true
                            ])))
                        ->create();

        $task = $project->tasks()->first();

        // 初期値を設定できるか
        $rendered = Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', 'content test')
            ->assertSet('isComplete', true)
            ->assertSeeLivewire(TaskDetail::class);
        
        // dispatchできるか
        $rendered
            ->set([
                'name'    => 'update name test',
                'content' => 'update content test',
            ])
            ->call('update')
            ->assertSet('name', 'update name test')
            ->assertSet('content', 'update content test')
            ->assertDispatched('refetch')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }

    public function test_タスクのチェックボックスを更新してdispatchできるか()
    {
        $this->actingAs(User::factory()->create());

        $content = "- [ ] content\n- [ ] content2";
        $updated = "- [|] content\n- [|] content2";
        
        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => $content,
                                'is_complete' => true
                            ])))
                        ->create();

        $task = $project->tasks()->first();

        // 初期値を設定できるか
        $rendered = Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', $content)
            ->assertSet('isComplete', true)
            ->assertSeeLivewire(TaskDetail::class);
        
        // dispatchできるか
        $rendered
            ->dispatch('updateCheckbox', $updated)
            ->assertDispatched('refetch')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }

    public function test_タスクの並び替えでdispatchできるか()
    {
        $this->actingAs(User::factory()->create());

        $content = "- [ ] content\n- [ ] content2";
        $updated = "- [ ] content2\n- [ ] content";
        
        $project = Project::factory()
                        ->has(
                            Task::factory()
                            ->state(new Sequence([
                                'name'        => 'name test',
                                'content'     => $content,
                                'is_complete' => true
                            ])))
                        ->create();

        $task = $project->tasks()->first();

        // 初期値を設定できるか
        $rendered = Livewire::test(TaskDetail::class, ['taskId' => $task->id])
            ->assertSet('name', 'name test')
            ->assertSet('content', $content)
            ->assertSet('isComplete', true)
            ->assertSeeLivewire(TaskDetail::class);
        
        // dispatchできるか
        $rendered
            ->dispatch('reorder', $updated)
            ->assertDispatched('refetch')
            ->assertDispatched('notify', message: Message::createSavedMessage()->toArray());
    }
}