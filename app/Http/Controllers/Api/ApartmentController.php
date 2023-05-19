<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// importo i model da utilizzare nelle richieste
use App\Models\Apartment;
use App\Models\Plan;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartments = Apartment::where('visible', '=', 1)
            ->with('plans', 'services')
            ->paginate(12);
        foreach ($apartments as $apartment) {
            $apartment->image = $apartment->getImage();
        }
        return response()->json($apartments);
    }

    /**
     * Display the details of the requested resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $apartment = Apartment::where('id', '=', $id)
            ->with('plans', 'services')
            ->first();
        $apartment->image = $apartment->getImage();
        return response()->json($apartment);
    }

    /**
     * Display the list of the requested resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function advancedSearch($latitude, $longitude, $distance, $unit = 'kilometers')
    {
        $apartments_inside_circle = [];
        $apartments = Apartment::where('visible', '=', 1)->with('services', 'plans')->get();
        foreach ($apartments as $apartment) {
            $theta = $longitude - $apartment->longitude;
            $distanceBetween = (sin(deg2rad($latitude)) * sin(deg2rad($apartment->latitude))) + (cos(deg2rad($latitude)) * cos(deg2rad($apartment->latitude)) * cos(deg2rad($theta)));
            $distanceBetween = acos($distanceBetween);
            $distanceBetween = rad2deg($distanceBetween);
            $distanceBetween = $distanceBetween * 60 * 1.1515;
            switch ($unit) {
                case 'miles':
                    break;
                case 'kilometers':
                    $distanceBetween = $distanceBetween * 1.609344;
            }
            if ($distanceBetween <= $distance) $apartments_inside_circle[] = $apartment;
            $distanceBetween = 0;
        }

        return response()->json($apartments_inside_circle);
    }


    /**
     * 
     * ## RETURN ACTIVE SPONSORED APARTMENTS ONLY ##
     * Ritorna gli appartamenti con un piano sponsorizzato attualmente attivo.
     * La lista generata mostrerà gli appartamenti, compresi di servizi. L'array plans 
     * per ogni appartamento includerà soltanto i piani attualmente in vigore.
     * 
     * Costruzione richiesta: /api/sponsored/{piano}/{max}/{random}
     *      {piano} = filtra per piano di sponsorizzazione.
     *                valori: 'all', null, o uno dei piani esistenti (es. 'bronze').
     *                Per i filtri successivi su tutta la lista è necessario 'all'.
     *        {max} = solo se presente {piano}, determina il numero massimo di risultati. Se non è indicato, verranno inviati tutti.
     *     {random} = solo se presenti {piano} (o 'all') e {max}, i risultati forniti da max saranno random.
     * 
     * */
    public function getSponsored($plan = null, $max = null, $random = false)
    {
        // Se il piano non è null ma la parola indicata non è all o non esiste
        if ($plan && !Plan::where('title', $plan)->exists() && $plan != 'all')
            return response()->json(['error' => 'Il piano specificato non esiste'], 404);

        $currentDate = Carbon::now()->toDateString(); // Data attuale
        $query = Apartment::where('visible', '=', '1')
            ->whereHas('plans', function ($query) use ($currentDate, $plan) {
                $query->where('end_date', '>', $currentDate); // Filtra per piani attivi
                if ($plan && $plan != 'all') $query->where('title', $plan); // Se è indicato un piano ed è diverso da all
            })
            ->with(['plans' => function ($query) use ($currentDate) {
                $query->where('end_date', '>', $currentDate); // Mostra solo piani attivi nella lista
            }])
            ->with('services');
        if ($random) $query->inRandomOrder(); // Se è specificato, selezioniamo risultati random
        if ($max) $query->take($max); // Se è indicato, ottieni il numero di risultati richiesti
        $query->orderBy('updated_at', 'desc'); // Ordina per ultimo aggiornamento

        $apartments = $query->get();
        return response()->json($apartments);
    }


    /**
     * 
     * ## RETURN STANDARD APARTMENTS ONLY ##
     * Ritorna gli appartamenti senza piani sponsorizzati attualmente attivi.
     * La lista includerà piani e servizi.
     *  
     * */

    public function getStandard()
    {
        $currentDate = Carbon::now()->toDateString(); // Data attuale
        $apartments = Apartment::where('visible', '=', '1')
            ->where(function ($query) use ($currentDate) {
                $query->whereDoesntHave('plans') // Ottieni gli appartamenti senza sponsorizzazioni
                    ->orWhereHas('plans', function ($query) use ($currentDate) {
                        $query->where('end_date', '<', $currentDate); // O con sponsorizzazione scadute
                    });
            })
            ->with('plans', 'services')
            ->get();
        return response()->json($apartments);
    }
};
