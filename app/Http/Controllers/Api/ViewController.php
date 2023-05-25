<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            return response()->json('ciao');
        } else return response()->json("ip esistente");
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
