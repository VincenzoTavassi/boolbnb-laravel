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
        $servizi = [
            'Wi-Fi',
            'Posto Auto',
            'Piscina',
            'Aria condizionata',
            'Colazione inclusa',
            'Accesso per disabili',
            'Terrazza panoramica',
            'Servizio di pulizia giornaliero',
            'Cucina completamente attrezzata',
            'TV via cavo/satellite',
            'Animali domestici ammessi',
            'Servizio di lavanderia',
            'Navetta aeroportuale',
            'Area barbecue',
            'Noleggio biciclette',
            'Self check-in',
            'Sauna',
            'Vista Mare',
            'Portineria',

        ];

        foreach ($servizi as $servizio) {
            $service = new Service;
            $service->title = $servizio;
            $service->icon = 'https://icongaga-api.bytedancer.workers.dev/api/genHexer?name=' . $servizio;
            $service->save();
        }
    }
}
