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
        $apartments = Apartment::where('id', '>=', 1)
            ->with('plans')
            ->paginate(12);
        return response()->json($apartments);
    }
}
