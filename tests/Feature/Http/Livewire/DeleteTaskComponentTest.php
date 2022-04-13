<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\DeleteTaskComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\DeleteTaskComponent */
class DeleteTaskComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function assert_delete_task_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(DeleteTaskComponent::class));
    }

    /** @test */
    public function admin_can_delete_user()
    {
        // delete-review
        $task = TaskFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(DeleteTaskComponent::class, ['task' =>  $task])
            ->call('destroy')
            ->assertEmitted('entity-deleted')
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(Task::find($task->id));
    }
}
