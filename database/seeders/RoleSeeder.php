<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = Role::create(['guard_name' => 'api', 'name' => 'admin']);
        $delivery = Role::create(['guard_name' => 'api', 'name' => 'deliverer']);
        $customer = Role::create(['guard_name' => 'api', 'name' => 'customer']);

        Permission::create(['guard_name' => 'api', "name" => "users.index"])->assignRole($admin);
        Permission::create(['guard_name' => 'api', "name" => "users.create"])->assignRole($admin);
        Permission::create(['guard_name' => 'api', "name" => "users.update"])->assignRole($admin);
        Permission::create(['guard_name' => 'api', "name" => "users.destroy"])->assignRole($admin);
        Permission::create(['guard_name' => 'api', "name" => "categories.index"])->syncRoles([$customer, $admin]);
        Permission::create(['guard_name' => 'api', "name" => "nomenclatures.index"])->syncRoles([$customer, $admin]);
        Permission::create(['guard_name' => 'api', "name" => "products.MorePopular"])->syncRoles([$customer, $admin]);
        Permission::create(['guard_name' => 'api', "name" => "products.ByCategory"])->syncRoles([$customer, $admin]);
        Permission::create(['guard_name' => 'api', "name" => "products.BySearch"])->syncRoles([$customer, $admin]);
        Permission::create(['guard_name' => 'api', "name" => "products.store"])->assignRole($admin);
        Permission::create(['guard_name' => 'api', "name" => "products.show"])->assignRole($customer);
    }
}
