<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apartment;
use Carbon\Carbon;
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
            // Roma
            [41.9028, 12.4964],
            [41.8919, 12.5113],
            [41.8976, 12.4901],
            [41.9097, 12.4763],
            [41.9073, 12.4713],
            [41.9045, 12.4887],
            [41.8991, 12.4859],
            [41.8982, 12.5009],
            [41.8936, 12.5027],
            [41.8889, 12.4969],
            [41.8964, 12.5076],
            [41.9092, 12.4828],
            [41.8948, 12.4757],
            [41.9059, 12.4951],
            [41.8907, 12.4962],

            // Milano
            [45.4642, 9.1900],
            [45.4707, 9.1951],
            [45.4689, 9.1935],
            [45.4619, 9.1817],
            [45.4674, 9.1837],
            [45.4723, 9.1844],
            [45.4696, 9.1895],
            [45.4638, 9.1912],
            [45.4692, 9.1947],
            [45.4664, 9.1908],
            [45.4649, 9.1880],
            [45.4710, 9.1932],
            [45.4682, 9.1853],
            [45.4656, 9.1919],
            [45.4721, 9.1876],

            // Napoli
            [40.8522, 14.2681],
            [40.8456, 14.2561],
            [40.8498, 14.2753],
            [40.8444, 14.2692],
            [40.8501, 14.2659],
            [40.8562, 14.2648],
            [40.8487, 14.2707],
            [40.8536, 14.2738],
            [40.8468, 14.2663],
            [40.8551, 14.2621],
            [40.8575, 14.2573],
            [40.8539, 14.2683],
            [40.8514, 14.2720],
            [40.8475, 14.2685],
            [40.8507, 14.2652],

            // Venezia
            [45.4372, 12.3358],
            [45.4387, 12.3351],
            [45.4347, 12.3396],
            [45.4321, 12.3385],
            [45.4309, 12.3356],
            [45.4346, 12.3339],
            [45.4369, 12.3314],
            [45.4391, 12.3342],
            [45.4327, 12.3365],
            [45.4397, 12.3373],
            [45.4378, 12.3397],
            [45.4353, 12.3325],
            [45.4339, 12.3354],
            [45.4381, 12.3331],
            [45.4363, 12.3328],
        ];

        for ($i = 0; $i < 60; $i++) {
            $apartment = new Apartment;
            $apartment->title = 'Appartamento in ' . $faker->city();
            $apartment->description = $faker->paragraph(3);
            $apartment->image = 'https://picsum.photos/500/500?random=' . $i;
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
            $service_iterations = rand(1, 8);
            $services_array = [];
            for ($x = 0; $x < $service_iterations; $x++) { // Servizi random, min. 1 max 8
                $service_number = rand(1, 15);
                if (!in_array($service_number, $services_array)) $services_array[] = $service_number;
            }
            foreach ($services_array as $service_id) {
                $apartment->services()->attach($service_id);
            }
            $plan_number = $faker->numberBetween(1, 3);
            $plan_iterations = rand(0, 5); // Piani random, min 0 max 5
            $currentDate = Carbon::now();
            $getsActivePlan = rand(0, 1); // Rolla 0 o 1 per decidere se ci saranno piani attivi
            if ($getsActivePlan) {
                $apartment->plans()->attach($plan_number, [ // Inserisci piano attivo
                    'start_date' => $faker->dateTime(),
                    'end_date' => $currentDate->addHours(rand(0, 240)),
                ]);
            }
            for ($k = 0; $k < $plan_iterations; $k++) { // Piani scaduti
                $apartment->plans()->attach($plan_number, [
                    'start_date' => $faker->dateTime(),
                    'end_date' => $faker->dateTime(),
                ]);
            }
        }
    }
}
