<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\EditBlaComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Bla;
use Database\Factories\BlaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\EditBlaComponent */
class EditBlaComponentTest extends TestCase
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
    public function assert_edit_bla_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(EditBlaComponent::class));
    }

    /** @test */
    public function render()
    {
        $bla = BlaFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditUserComponent::class, ['bla' => $bla])
            // edit-review
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function update_existing_user()
    {
        $bla = BlaFactory::new()->create();

        $this->assertCount(1, Bla::all());

        Livewire::actingAs($this->admin)
            ->test(EditBlaComponent::class, ['bla' => $bla])
            // edit-review
            ->call('update')
            ->assertRedirect('blas');

        $this->assertTrue(session()->has('flash'));

        // edit-review
        //$blas = Bla::where()->get();

        $this->assertCount(1, blas);

        $this->assertCount(1, Bla::all());
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_update_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        $bla = BlaFactory::new()->create();

        Livewire::actingAs($this->admin)
            ->test(EditBlaComponent::class, ['bla' => $bla])
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
