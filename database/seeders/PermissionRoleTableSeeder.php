<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff_permissions = Permission::where('group', 'tasks')->get();
        Role::findOrFail(2)->permissions()->sync($staff_permissions->pluck('id'));
    }
}
