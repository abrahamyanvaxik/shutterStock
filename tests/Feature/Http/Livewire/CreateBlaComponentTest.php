<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\CreateBlaComponent;
use App\Http\Livewire\HasLivewireAuth;
use App\Models\Bla;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Livewire\Livewire;
use Tests\TestCase;

/** @see \App\Http\Livewire\CreateBlaComponent */
class CreateBlaComponentTest extends TestCase
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
    public function assert_create_bla_component_uses_livewire_auth_trait()
    {
        $this->assertContains(HasLivewireAuth::class, class_uses(CreateBlaComponent::class));
    }

    /** @test */
    public function render()
    {
        Livewire::actingAs($this->admin)
            ->test(CreateBlaComponent::class)
            ->assertSee('Save')
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function store()
    {
        Livewire::actingAs($this->admin)
            ->test(CreateBlaComponent::class)
            // create-review-2
            ->call('store')
            ->assertRedirect('blas');

        $this->assertTrue(session()->has('flash'));

        // create-review
        $blas = Bla::where('name', 'staff')
            ->where('label', 'Staff')
            ->get();

        $this->assertCount(1, $blas);
    }

    /**
     * @test
     * @dataProvider clientFormValidationProvider
     */
    public function test_store_validation_rules($clientFormInput, $clientFormValue, $rule)
    {
        Livewire::actingAs($this->admin)
            ->test(CreateBlaComponent::class)
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
