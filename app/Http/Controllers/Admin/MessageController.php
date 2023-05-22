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
    public function show(Apartment $apartment,Message $message)
    {
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return back()
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }

        return view('admin.messages.show', compact('apartment', 'message'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function destroy(Message $message)
    {
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return back()
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }

        $message->delete();

        return redirect()->route('message.index');
    }

    /**
     * Force-delete the specified resource from storage.
     *
     * @param  \App\Models\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(int $id)
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
