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
        $apartments = Apartment::where('visible', '=', 1)->get();
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
}
