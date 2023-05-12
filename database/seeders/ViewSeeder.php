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
        //$labels = ["", "", "", "", "", ""];

        foreach ($labels as $label) {
            $view = new View();
            $view->date = $faker->dateTime();
            $view->ip_address = $faker->;
            $view->save();
        }
    }
}
