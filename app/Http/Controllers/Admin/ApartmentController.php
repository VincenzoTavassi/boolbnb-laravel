<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;



class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $apartments = Apartment::where('user_id', '=', $user_id)->Paginate(4);
        return view('admin.apartments.index', compact('apartments', 'user_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        return view('admin.apartments.form', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $data = $this->validation($request->all());
        $apartment = new Apartment();
        $apartment->fill($data);
        $apartment->latitude = 0;
        $apartment->longitude = 0;
        $apartment->user_id = $user_id;
        if (!Arr::exists($data, 'visible')) $apartment->visible = 0;
        $apartment->save();
        $apartment->services()->attach($data['services']);
        return to_route('apartments.create')->with('message', 'Appartamento creato con successo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        //
    }

    ## FUNCTIONS

    private function validation($data)
    {

        return Validator::make(

            $data,
            [
                'title' => 'required|max:100',
                'description' => 'nullable|max:2500',
                // 'image' => 'nullable|image|mimes:jpg,jpeg,bmp,png',
                'image' => 'nullable|max:200',
                'price' => 'required|numeric',
                'single_beds' => 'required|numeric',
                'double_beds' => 'required|numeric',
                'bathrooms' => 'required|numeric',
                'square_meters' => 'required|numeric',
                'rooms' => 'required|numeric',
                'address' => 'required|max:100',
                'latitude' => 'float',
                'longitude' => 'float',
                'visible' => 'nullable|boolean',
                'services' => 'required|exists:services,id',

            ],
        )->validate();
    }
}
