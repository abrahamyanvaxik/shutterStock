<?php

namespace Tests\Unit\Filters;

use App\Models\Role;
use Database\Factories\RoleFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Filters\RoleFilter */
class RoleFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_roles_by_name()
    {
        $writer = RoleFactory::new()->create([
            'name' => 'writer',
        ]);

        $staff = RoleFactory::new()->create([
            'name' => 'staff',
        ]);

        $result = Role::filter([
                'search' => 'staff',
            ])->get();

        $this->assertCount(1, $result);

        $this->assertTrue($result->contains($staff));
    }

    /** @test */
    public function order_roles_by_field()
    {
        $writer = RoleFactory::new()->create([
            'name' => 'writer',
        ]);

        $staff = RoleFactory::new()->create([
            'name' => 'staff',
        ]);

        $result = Role::filter([
                'orderByField' => ['name', 'asc'],
            ])->get();

        $this->assertTrue(collect([$staff->id, $writer->id]) == $result->pluck('id'));
    }
}
