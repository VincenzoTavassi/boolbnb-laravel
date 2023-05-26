<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\View;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($days = 6, $json = false)
    {
        $user = Auth::user();
        $apartments = $user->apartments;
        // Creo intervallo di date
        $startDate = Carbon::createFromFormat('Y-m-d', Carbon::now()->subDays($days)->toDateString());
        $endDate = Carbon::createFromFormat('Y-m-d', Carbon::now()->toDateString());
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        // Array di date dell'ultima settimana
        $dates = $dateRange->toArray();

        // Per ogni view di ogni appartamento
        foreach ($apartments as $apartment) {
            $date_views = [];
            // Inserisco le date nell'array date_views
            foreach ($dates as $date) {
                $date_views[$date->toDateString()] = 0;
            }
            $views = $apartment->views()
                ->where('date', '>', $startDate->toDateString())
                ->get(); // Prendi le views maggiori della start date definita
            foreach ($views as $view) {
                // Per ogni view, se presente la data nell'array, aggiorna il contatore
                if (array_key_exists($view->date, $date_views)) $date_views[$view->date]++;
            }
            $apartment->date_views = $date_views;
        }
        if ($json) return response()->json($apartments);
        else return view('admin.home', compact('apartments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $apartment_id = $data['apartment_id'];
        $ip_address = $data['ip_address'];
        $apartment = Apartment::findOrFail($data['apartment_id']);
        $currentDate = Carbon::now()->toDateString();

        $found_ip = $apartment->views()
            ->where('date', $currentDate)
            ->where('ip_address', $ip_address)
            ->exists();

        if (!$found_ip) {
            $view = new View;
            $view->date = $currentDate;
            $view->ip_address = $ip_address;
            $view->apartment_id = $apartment_id;
            $view->save();
            return response()->json(['success' => true]);
        } else return response()->json(['error' => 'ip esistente ' . $ip_address]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
