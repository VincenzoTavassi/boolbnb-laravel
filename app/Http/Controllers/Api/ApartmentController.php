<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// importo i model da utilizzare nelle richieste
use App\Models\Apartment;
use App\Models\Service;

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
            ->with('plans')
            ->paginate(12);
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
            ->get();
        return response()->json($apartment);
    }

    /**
     * Display the list of the requested resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function advancedSearch($rooms, $beds)
    {
        // se voglio sapere solo il numero di stanze
        if ($beds == 0) {
            $apartment = Apartment::where('rooms', '>=', $rooms)
                ->with('plans', 'services')
                ->get();
        }

        // se voglio sapere solo il numero di stanze letti disponibili
        if ($rooms == 0) {
            $apartment = Apartment::whereRaw("`single_beds`+`double_beds` >= $beds")
                ->with('plans', 'services')
                ->get();
        }

        // se voglio sapere sia il numero di stanze che di letti
        if ($rooms > 0 & $beds > 0) {
            $apartment = Apartment::where('rooms', '>=', $rooms)
                ->whereRaw("`single_beds`+`double_beds` >= $beds")
                ->with('plans', 'services')
                ->get();
        }
        return response()->json($apartment);
    }
}
