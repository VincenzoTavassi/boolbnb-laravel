<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $user = new User;
        $user->email = 'ciao@ciao.com';
        $user->password = bcrypt('password');
        $user->name = 'Paperino';
        $user->surname = 'Papero';
        $user->save();

        for ($i = 0; $i < 15; $i++) {
            $user = new User;
            $user->email = $faker->email();
            $user->password = bcrypt('password');
            $user->name = $faker->firstName($gender = 'male'|'female');
            $user->surname = $faker->firstName($gender = 'male'|'female');
            $user->save();
        }
    }
    
}
