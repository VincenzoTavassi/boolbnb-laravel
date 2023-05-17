<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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
        $apartment = new Apartment;
        $services = Service::all();
        return view('admin.apartments.form', compact('services', 'apartment'));
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
        if (Arr::exists($data, 'image')) { // Se c'è un'immagine nell'array $data
            $path = Storage::put('uploads', $data['image']); // Ottieni il path e salvala nella cartella uploads
            $data['image'] = $path; // Il dato da salvare in db diventa il path dell'immagine
        }
        $apartment = new Apartment();
        $apartment->fill($data);
        $apartment->user_id = $user_id;
        if (array_key_exists('visible', $data)) $apartment->visible = 1;
        else $apartment->visible = 0;
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
        $services = Service::all();
        return view('admin.apartments.show', compact('apartment', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit(Apartment $apartment)
    {
        $services = Service::all();
        $apartment_services = $apartment->services->pluck('id')->toArray();
        return view('admin.apartments.form', compact('apartment', 'services', 'apartment_services'));
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
        $data = $this->validation($request->all());
        if (Arr::exists($data, 'image')) { // Se c'è un'immagine nell'array $data
            if ($apartment->image) {
                Storage::delete($apartment->image); // Elimina la vecchia immagine se presente
            }
            $path = Storage::put('uploads', $data['image']); // Ottieni il path della nuova e salvala nella cartella uploads
            $data['image'] = $path; // Il dato da salvare in db diventa il path dell'immagine
        }
        // Se il form invia il contenuto delle checkbox (la chiave 'services' esiste), aggiorna la relazione
        if (array_key_exists('services', $data)) $apartment->services()->sync($data['services']);
        else $apartment->services()->detach(); // Altrimenti elimina tutte le associazioni
        $apartment->update($data);
        if (array_key_exists('visible', $data)) $apartment->visible = 1;
        $apartment->save();
        return to_route('apartments.edit', $apartment)->with('message', 'Appartamento modificato con successo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        $apartment->delete();
        return redirect()->route('apartments.index');
    }

    ## FUNCTIONS TRASHED RESOURCE.
    public function trash(Request $request)
    {
        $apartments = Apartment::onlyTrashed()->get();

        return view('admin.apartments.trash', compact('apartments'));
    }

    /**
     * Force-delete the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(int $id)
    {
        $apartment = Apartment::where('id', $id)->onlyTrashed()->first();

        if ($apartment->image) Storage::delete($apartment->delete());
        $apartment->forcedelete();
        return redirect()->route('admin.apartments.trash');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        $apartment = Apartment::where('id', $id)->onlyTrashed()->first();
        $apartment->restore();

        return to_route('apartments.index');
    }

    ## VALIDATION
    private function validation($data)
    {

        return Validator::make(

            $data,
            [
                'title' => 'required|max:100',
                'description' => 'nullable|max:2500',
                'image' => 'nullable|image|mimes:jpg,jpeg,bmp,png',
                // 'image' => 'nullable|max:200',
                'price' => 'required|numeric',
                'single_beds' => 'required|numeric',
                'double_beds' => 'required|numeric',
                'bathrooms' => 'required|numeric',
                'square_meters' => 'required|numeric',
                'rooms' => 'required|numeric',
                'address' => 'required|max:100',
                'latitude' => 'numeric',
                'longitude' => 'numeric',
                'visible' => 'nullable|boolean',
                'services' => 'required|exists:services,id',

            ],
        )->validate();
    }
}
