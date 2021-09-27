<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::truncate();
        $faker = \Faker\Factory::create();
        $password = Hash::make("test");
        
        $userTeacher = User::create([
            'firstname' => $faker->firstname,
            'lastname' => $faker->lastname,
            'email' => 'teacher@test.fr',
            'password' => $password,
            'roles' => ['ROLE_TEACHER'],
            'email_verified_at' => now()
        ]); 

        $userStudent = User::create([
            'firstname' => $faker->firstname,
            'lastname' => $faker->lastname,
            'email' => 'student@test.fr',
            'password' => $password,
            'roles' => ['ROLE_STUDENT'],
            'email_verified_at' => now()
        ]);

        $userStudent2 = User::create([
            'firstname' => 'Samuel',
            'lastname' => 'CARDOSO',
            'email' => 'samuel@test.fr',
            'password' => $password,
            'roles' => ['ROLE_STUDENT'],
            'email_verified_at' => now()
        ]);       
    }
}
