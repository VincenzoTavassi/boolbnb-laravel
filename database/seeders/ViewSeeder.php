<?php

namespace Database\Seeders;

use Faker\Generator as Faker;
use App\Models\View;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        
        for ($i = 0; $i < 15; $i++) {
            $view = new View();
            $view->date = $faker->dateTimeThisMonth();
            $view->ip_address = $faker->localIpv4();
            $view->apartment_id = $faker->randomElement([19, 54, 12]);

            // $view->date = $faker->dateTimeThisMonth();
            // $view->ip_address = $faker->localIpv4();
            // $view->apartment_id = $faker->randomDigit(19, 54, 12);
            $view->save();
        }
    }
}
