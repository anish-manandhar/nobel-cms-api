<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Biplab Neupane',
            'email' => 'biplab@gmail.com',
            'email_verified_at' => now(),
            'date_of_birth' => Carbon::now()->subYear(20),
            'password' => bcrypt('biplab'),
            'phone' => '9800000000',
            'address' => 'Airport',
            'gender' => 'male'
        ]);

        $user->assignRole(User::ADMIN);
        $user->addMedia(resource_path() . '/images/avatar.png')->preservingOriginal()->toMediaCollection('profile');
        $user->employees_details()->saveMany(Employee::factory()->count(1)->make());

        $user = User::create([
            'name' => 'Nobel College',
            'email' => 'admin@nobel.com',
            'email_verified_at' => now(),
            'date_of_birth' => Carbon::now()->subYear(20),
            'password' => bcrypt('nobel'),
            'phone' => '9800000000',
            'address' => 'Sinamangal',
            'gender' => 'male'
        ]);

        $user->assignRole(User::ADMIN);
        $user->addMedia(resource_path() . '/images/avatar.png')->preservingOriginal()->toMediaCollection('profile');
        $user->employees_details()->saveMany(Employee::factory()->count(1)->make());

        // $roles = Role::get()->toArray();
        // User::factory()->count(10)->create()->each(function ($user) use ($roles) {
        //     $user->assignRole(array_rand($roles));

        //     if ($user->hasRole('Student'))
        //         $user->students()->saveMany(Student::factory()->count(1)->make());
        //     else
        //         $user->employees()->saveMany(Employee::factory()->count(1)->make());

        //     $user->addMedia(resource_path() . '/images/avatar.png')->preservingOriginal()->toMediaCollection('profile');
        // });
    }
}
