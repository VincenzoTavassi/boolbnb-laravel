<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Apartment;
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

        for ($i = 0; $i < 50; $i++) {
            $num = $faker->numberBetween(1, 20);
            $apartment = Apartment::where('id', '=', $num)->first();
            Message::create([
                'text' => $faker->sentence(),
                'email' => $faker->email(),
                'name' => $faker->name(),
                'apartment_id' => $num,
                'user_id' => $apartment->user_id,
            ]);
        }
    }
}

