<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\DeleteBlaComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Bla;
use Database\Factories\BlaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\DeleteBlaComponent */
class DeleteBlaComponentTest extends TestCase
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
    public function assert_delete_bla_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(DeleteBlaComponent::class));
    }

    /** @test */
    public function admin_can_delete_user()
    {
        // delete-review
        $bla = BlaFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(DeleteBlaComponent::class, ['bla' =>  $bla])
            ->call('destroy')
            ->assertEmitted('entity-deleted')
            ->assertDispatchedBrowserEvent('flash');

        $this->assertNull(Bla::find($bla->id));
    }
}
