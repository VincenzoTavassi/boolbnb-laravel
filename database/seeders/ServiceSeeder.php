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
    public function run()
    {
        $servizi = [
            [
                'title' => 'Wi-Fi',
                'icon' => 'bi-wifi',
            ],
            [
                'title' => 'Piscina',
                'icon' => 'bi-water',
            ],
            [
                'title' => 'Posto auto',
                'icon' => 'bi-car-front-fill',
            ],
            [
                'title' => 'Aria condizionata',
                'icon' => 'bi-snow',
            ],
            [
                'title' => 'Terrazza panoramica',
                'icon' => 'bi-binoculars-fill',
            ],
            [
                'title' => 'Servizio di pulizia',
                'icon' => 'bi-stars',
            ],
            [
                'title' => 'TV via cavo/satellite',
                'icon' => 'bi-tv-fill',
            ],
            [
                'title' => 'Servizio lavanderia',
                'icon' => 'bi-fan',
            ],
            [
                'title' => 'Navetta aeroportuale',
                'icon' => 'bi-bus-front-fill',
            ],
            [
                'title' => 'Area barbecue',
                'icon' => 'bi-tree-fill',
            ],
            [
                'title' => 'Noleggio bici',
                'icon' => 'bi-bicycle',
            ],
            [
                'title' => 'Self check-in',
                'icon' => 'bi-clipboard-check-fill',
            ],
            [
                'title' => 'Colazione inclusa',
                'icon' => 'bi-cup-hot-fill',
            ],
            [
                'title' => 'Sauna',
                'icon' => 'bi-thermometer-high',
            ],
            [
                'title' => 'Vista mare',
                'icon' => 'bi-tsunami',
            ],
            [
                'title' => 'Portineria',
                'icon' => 'bi-person-square',
            ],
            /*'-Wi-Fi',
            '-Posto Auto',
            '-Piscina',
            '-Aria condizionata',
            '-Colazione inclusa',
            'Accesso per disabili',
            '-Terrazza panoramica',
            '-Servizio di pulizia giornaliero',
            'Cucina completamente attrezzata',
            '-TV via cavo/satellite',
            'Animali domestici ammessi',
            '-Servizio di lavanderia',
            '-Navetta aeroportuale',
            '-Area barbecue',
            '-Noleggio biciclette',
            '-Self check-in',
            '-Sauna',
            '-Vista Mare',
            '-Portineria',*/
        ];

        foreach ($servizi as $servizio) {
            $service = new Service;
            $service->title = $servizio['title'];
            $service->icon = $servizio['icon'];
            $service->save();
        }

        /*foreach ($servizi as $servizio) {
            $service = new Service;
            $service->title = $servizio;
            $service->icon = 'https://icongaga-api.bytedancer.workers.dev/api/genHexer?name=' . $servizio;
            $service->save();
        }*/
    }
}
