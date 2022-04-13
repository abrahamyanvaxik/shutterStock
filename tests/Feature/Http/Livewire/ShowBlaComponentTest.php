<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\ShowBlaComponent;
use App\Models\Bla;
use Database\Factories\BlaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\ShowBlaComponent */
class ShowBlaComponentTest extends TestCase
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
        $bla = BlaFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(ShowBlaComponent::class, ['bla' => $bla])
            // show-review
            ->assertStatus(Response::HTTP_OK);
    }
}
