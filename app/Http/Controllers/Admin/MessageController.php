<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $apartments = Apartment::where('user_id', '=', $user_id)
            ->with('messages')
            ->get();
        return view('admin.messages.index', compact('apartments', 'user_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment, Message $message)
    {
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return back()
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }

        return view('admin.messages.show', compact('apartment', 'message'));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment, Message $message)
    {
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return back()
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }

        $message->delete();

        return redirect()->route('messages.index');
    }

    ## FUNCTIONS TRASHED RESOURCE.
    public function trash(Request $request)
    {
        $user_id = Auth::id();
        $apartments = Apartment::where('user_id', '=', $user_id)->onlyTrashed()->get();
        return view('admin.apartments.trash', compact('apartments'));
    }

    /**
     * Force-delete the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Apartment $apartment, Message $message)
    {
        $message = Message::where('id', $id)->onlyTrashed()->first();

        return redirect()->route('admin.messages.trash');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function restore(int $id)
    {
        $message = Message::where('id', $id)->onlyTrashed()->first();
        $message->restore();

        return to_route('messages.index');
    }
}
