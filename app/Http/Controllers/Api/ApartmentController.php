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

        foreach ($apartments_inside_circle as $apartment) {
            $apartment->image = $apartment->getImage();
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

        $currentDate = Carbon::now()->toDateTimeString(); // Data attuale
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
        $query->orderByDesc(function ($query) { // Ordina i risultati in base alla creazione del piano di sponsorizzazione
            $query->select('apartment_plan.start_date')
                ->from('plans')
                ->join('apartment_plan', 'plans.id', '=', 'apartment_plan.plan_id')
                ->whereColumn('apartments.id', 'apartment_plan.apartment_id')
                ->orderBy('apartment_plan.start_date', 'desc')
                ->limit(1);
        });

        $apartments = $query->get();
        foreach ($apartments as $apartment) {
            $apartment->image = $apartment->getImage();
        }
        return response()->json($apartments);
    }

    /**
     * 
     * ## RETURN STANDARD APARTMENTS ONLY ##
     * Ritorna gli appartamenti senza piani sponsorizzati attualmente attivi.
     * La lista includerà piani e servizi.
     *      * Costruzione richiesta: /api/standard/{max}/{random}
     *        {max} = determina il numero massimo di risultati. Se non è indicato, verranno inviati tutti.
     *     {random} = solo se presente {max}, i risultati forniti da max saranno random.
     * */

    public function getStandard($max = null, $random = false)
    {
        $currentDate = Carbon::now()->toDateTimeString(); // Data attuale
        $query = Apartment::where('visible', '=', '1')
            ->whereDoesntHave('plans') // Appartamenti senza sponsorizzazioni
            ->orWhereHas('plans', function ($query) use ($currentDate) {
                $query->where('end_date', '<', $currentDate); // Includi sponsorizzazioni scadute
            })
            ->whereDoesntHave('plans', function ($query) use ($currentDate) {
                $query->where('end_date', '>', $currentDate); // Escludiamo sponsorizzazioni attive
            })
            ->with('plans', 'services');
        if ($random) $query->inRandomOrder(); // Se è specificato, selezioniamo risultati random
        if ($max) $query->take($max); // Se è indicato, ottieni il numero di risultati richiesti
        $query->orderBy('updated_at', 'desc'); // Ordina per ultimo aggiornamento
        $apartments = $query->get();

        foreach ($apartments as $apartment) {
            $apartment->image = $apartment->getImage();
        }
        return response()->json($apartments);
    }
};
