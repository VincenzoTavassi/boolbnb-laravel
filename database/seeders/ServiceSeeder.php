<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 15; $i++) {
            $service = new Service;
            $service->title = $faker->word();
            $service->icon = 'https://icongaga-api.bytedancer.workers.dev/api/genHexer?name=' . $faker->word();
            $service->save();

            
        }
    }
}
