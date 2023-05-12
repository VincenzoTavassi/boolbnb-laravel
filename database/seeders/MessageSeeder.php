<?php

namespace Database\Seeders;

use App\Models\Message;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Message::create([
                'text' => $faker->sentence(),
                'email' => $faker->email(),
                'name' => $faker->name(),
            ]);
        }
    }
}

