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
        for ($i=0; $i < 20; $i++) {
            $apartment = new Apartment;
            $apartment->title = 'Appartamento in '.$faker->city();
            $apartment->description = $faker->paragraph(3);
            $apartment->image = 'https://picsum.photos/200/300';
            $apartment->price = $faker->randomFloat(2, 20, 300);
            $apartment->single_beds = $faker->randomDigitNot(2);
        }
    }
}
