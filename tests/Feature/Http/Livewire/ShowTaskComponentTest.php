<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\ShowTaskComponent;
use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\ShowTaskComponent */
class ShowTaskComponentTest extends TestCase
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
    public function render()
    {
        $task = TaskFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(ShowTaskComponent::class, ['task' => $task])
            // show-review
            ->assertStatus(Response::HTTP_OK);
    }
}
