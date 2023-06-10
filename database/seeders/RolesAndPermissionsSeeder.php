<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        Permission::updateOrCreate(['name' => 'view report']);
        Permission::updateOrCreate(['name' => 'recharge balance']);
        Permission::updateOrCreate(['name' => 'transfer balance']);
        Permission::updateOrCreate(['name' => 'create products']);
        Permission::updateOrCreate(['name' => 'update products']);
        Permission::updateOrCreate(['name' => 'delete products']);
        Permission::updateOrCreate(['name' => 'update user role']);
        Permission::updateOrCreate(['name' => 'delete user role']);

        $role = Role::updateOrCreate(['name' => 'admin']);
        $role->syncPermissions(Permission::all());

        $role = Role::updateOrCreate(['name' => 'balance_recharge']);
        $role->syncPermissions(Permission::where('name', 'recharge balance')->get());

        $role = Role::updateOrCreate(['name' => 'balance_transfer']);
        $role->syncPermissions(Permission::where('name', 'transfer balance')->get());

        $role = Role::updateOrCreate(['name' => 'user']);


        $user = User::UpdateOrCreate([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123456'),
        ]);
        $user->assignRole('admin');

        $user = User::UpdateOrCreate([
            'name' => 'user_a',
            'email' => 'user_a@test.com',
            'password' => bcrypt('user_a123456'),
        ]);
        $user->assignRole('user');

        $user = User::UpdateOrCreate([
            'name' => 'user_b',
            'email' => 'user_b@test.com',
            'password' => bcrypt('user_b123456'),
        ]);
        $user->assignRole('user');

        $user = User::UpdateOrCreate([
            'name' => 'sub_admin',
            'email' => 'sub_admin@test.com',
            'password' => bcrypt('sub_admin123456'),
        ]);
        $user->assignRole('balance_recharge');

    }
}
