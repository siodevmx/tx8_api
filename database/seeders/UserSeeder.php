<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::create([
            'email' => 'isidro.ram@gmail.com',
            'password' => bcrypt('password'),
            'changed_password' => true
        ])->assignRole('admin');

        UserDetails::create([
            'user_id' => $userAdmin->id,
            'name' => 'Isidro Alfredo',
            'surnames' => 'Martinez',
            'phone' => '4771234567'
        ]);

        $userAdmin2 = User::create([
            'email' => 'esteban.mtz@outlook.com',
            'password' => bcrypt('password'),
            'changed_password' => true
        ])->assignRole('admin');

        UserDetails::create([
            'user_id' => $userAdmin2->id,
            'name' => 'Esteban',
            'surnames' => 'Martinez',
            'phone' => '47712309049'
        ]);


        $userDeliverer = User::create([
            'email' => 'omar.rmz@gmail.com',
            'password' => bcrypt('password'),
            'changed_password' => true
        ])->assignRole('deliverer');

        UserDetails::create([
            'user_id' => $userDeliverer->id,
            'name' => 'Omar',
            'surnames' => 'Leon',
            'phone' => '4775840938'
        ]);


        $userCustomer = User::create([
            'email' => 'chava.rmz@gmail.com',
            'password' => bcrypt('password'),
            'changed_password' => true
        ])->assignRole('customer');


        UserDetails::create([
            'user_id' => $userCustomer->id,
            'name' => 'Chava',
            'surnames' => 'Ramirez',
            'phone' => '4771029348',
        ]);

        // Usuarios random con rol random
        User::factory()->count(83)->create()->each(function ($user) {
//            $rolesArray = ['admin', 'customer', 'deliverer'];
            $userDetail = UserDetails::factory()->make(['user_id' => $user->id]);
//            $user->assignRole(Arr::random($rolesArray));
            $user->assignRole('customer');
            $user->userDetails()->save($userDetail);
        });
    }
}
