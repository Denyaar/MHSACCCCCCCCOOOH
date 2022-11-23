<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $adminRole = Role::whereName('Admin')->first();
        $userRole = Role::whereName('User')->first();

        // Seed test admin
        $seededAdminEmail = 'nmupezeni@gmail.com';
        $user = User::where('email', '=', $seededAdminEmail)->first();
        if ($user === null) {
            $user = User::create([
                'name' => 'nmupezeni',
                'first_name' => 'Tendai',
                'last_name' => 'Nyasha',
                'pay_number' => 'PAY0771',
                'email' => $seededAdminEmail,
                'password' => Hash::make('password'),
                'token' => str_random(64),
                'activated' => true,
                'signup_confirmation_ip_address' => '127.0.0.1',
                'admin_ip_address' => '127.0.0.1',
            ]);


            $user->attachRole($adminRole);
            $user->save();
        }

        // Seed test user
        $user = User::where('email', '=', 'user@user.com')->first();
        if ($user === null) {
            $user = User::create([
                'name' => 'User',
                'first_name' => 'Hermione',
                'last_name' => 'Granger',
                'email' => 'user@user.com',
                'pay_number' => 'PAY077',
                'password' => Hash::make('password'),
                'token' => str_random(64),
                'activated' => true,
                'signup_ip_address' => '127.0.0.1',
                'signup_confirmation_ip_address' => '127.0.0.1',
            ]);


            $user->attachRole($userRole);
            $user->save();
        }
    }
}
