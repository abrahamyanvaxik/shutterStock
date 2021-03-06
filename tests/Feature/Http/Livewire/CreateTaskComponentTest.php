<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\CreateTaskComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\CreateTaskComponent */
class CreateTaskComponentTest extends TestCase
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
    public function assert_create_task_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(CreateTaskComponent::class));
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTaskComponent::class)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTaskComponent::class)
            // create-review-2
            ->call('store')
            ->assertRedirect('tasks');

        $this->assertTrue(session()->has('flash'));

        // create-review
        $tasks = Task::where('name', 'staff')
            ->where('label', 'Staff')
            ->get();

        $this->assertCount(1, $tasks);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_store_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->admin)
            ->test(CreateTaskComponent::class)
            ->set($clientFormInput, $clientFormValue)
            ->call('store')
            ->assertHasErrors([$clientFormInput => $rule]);
    }

    public function clientFormValidationProvider()
    {
        return [
            // create-review
        ];
    }
}
