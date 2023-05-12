<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Faker\Generator as Faker;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 20; $i++) {
            $apartment = new Apartment;
            $apartment->title = 'Appartamento in ' . $faker->city();
            $apartment->description = $faker->paragraph(3);
            $apartment->image = 'https://picsum.photos/200/300';
            $apartment->price = $faker->randomFloat(2, 20, 300);
            $apartment->single_beds = $faker->numberBetween(1, 4);
            $apartment->double_beds = $faker->numberBetween(1, 4);
            $apartment->bathrooms = $faker->numberBetween(1, 4);
            $apartment->square_meters = $faker->numberBetween(100, 400);
            $apartment->rooms = $faker->numberBetween(1, 4);
            $apartment->address = $faker->streetAddress();
            $apartment->latitude = $faker->latitude();
            $apartment->longitude = $faker->longitude(2);
            $apartment->visible = $faker->boolean();
            $number = $faker->numberBetween(1, 15);
            $apartment->user_id = $number;
            $apartment->save();

            $service_number = $faker->numberBetween(1, 15);
            $apartment->services()->attach($service_number);
            $plan_number = $faker->numberBetween(1, 3);
            $plan_iterations = rand(0, 5);
            for ($k = 0; $k < $plan_iterations; $k++) {
                $apartment->plans()->attach($plan_number, [
                    'start_date' => $faker->dateTime(),
                    'end_date' => $faker->dateTime()
                ]);
            }
        }
    }
}
