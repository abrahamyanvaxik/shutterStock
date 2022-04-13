<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\EditTaskComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\EditTaskComponent */
class EditTaskComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    protected $admin;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = create_admin();
    }

    /** @test */
    public function assert_edit_task_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(EditTaskComponent::class));
    }

    /** @test */
    public function render()
    {
        $task = TaskFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['task' => $task])
            // edit-review
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update_existing_user()
    {
        $task = TaskFactory::new()->create();

        $this->assertCount(1, Task::all());

        Livewire::actingAs($this->admin)
            ->test(EditTaskComponent::class, ['task' => $task])
            // edit-review
            ->call('update')
            ->assertRedirect('tasks');

        $this->assertTrue(session()->has('flash'));

        // edit-review
        //$tasks = Task::where()->get();

        $this->assertCount(1, tasks);

        $this->assertCount(1, Task::all());
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $task = TaskFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditTaskComponent::class, ['task' => $task])
            ->set($clientFormInput, $clientFormValue)
            ->call('update')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            // edit-review
        ];
    }
}
