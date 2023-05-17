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
        $coordinates = [
            //roma
             [ 41.9028, 12.4964 ],
             [ 41.8919, 12.5113 ],
             [ 41.8976, 12.4901 ],
             [ 41.9097, 12.4763 ],
             [ 41.9073, 12.4713 ],
            //milano
             [ 45.4642, 9.1900 ],
             [ 45.4732, 9.1715 ],
             [ 45.8976, 9.1963 ],
             [ 45.9073, 9.1771 ],
             [ 45.9028, 9.1893 ],
            //napoli
             [ 40.8522, 14.2681 ],
             [ 40.8392, 14.2500 ],
             [ 40.8526, 14.2686 ],
             [ 40.8414, 14.2645 ],
             [ 40.8453, 14.2529 ],
            //venezia
             [ 45.4342, 12.3386 ],
             [ 45.4375, 12.3346 ],
             [ 45.4322, 12.3395 ],
             [ 45.4360, 12.3368 ],
             [ 45.4339, 12.3355 ],
        ];
        
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
            $apartment->latitude = $coordinates[$i][0];
            $apartment->longitude = $coordinates[$i][1];
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
