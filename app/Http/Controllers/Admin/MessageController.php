<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Apartment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
     * Display a listing of the resource ONLY OF THE APARTMENT REQUESTED.
     *
     * @return \Illuminate\Http\Response
     */
    public function listByApartment($apartment_id)
    {
        $user_id = Auth::id();
        $apartments_id = Apartment::where('user_id', '=', $user_id)
            ->pluck('id');
        $apartments = [];
        foreach ($apartments_id as $id) {
            if ($id == $apartment_id) {
                $apartments = Apartment::where('user_id', '=', $user_id)
                    ->where('id', '=', $apartment_id)
                    ->with('messages')
                    ->get();
            }
        }
        return view('admin.messages.index', compact('apartments', 'user_id'));
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
        $apartments = Apartment::where('user_id', '=', $user_id)
            ->get();
        $apartments_id = Apartment::where('user_id', '=', $user_id)
            ->pluck('id');
        $messagesList = [];
        foreach ($apartments_id as $apartment_id) {
            $messages = Message::onlyTrashed()
                ->where('apartment_id', '=', $apartment_id)
                ->get();
            $messagesList[] = $messages;
        };
        return view('admin.messages.trash', compact('messagesList', 'apartments'));
    }

    /**
     * Force-delete the specified resource from storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($apartment_id, $message_id)
    {
        $user_id = Auth::id();
        $apartments_id_list = Apartment::where('user_id', '=', $user_id)
            ->pluck('id');
        foreach ($apartments_id_list as $id) {
            if ($id == $apartment_id) {
                $message = Message::where('id', $message_id)->onlyTrashed()->first();
                $message->forcedelete();
            }
        };
        return redirect()->route('admin.messages.trash');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($apartment_id, $message_id)
    {
        $user_id = Auth::id();
        $apartments_id_list = Apartment::where('user_id', '=', $user_id)
            ->pluck('id');
        foreach ($apartments_id_list as $id) {
            if ($id == $apartment_id) {
                $message = Message::where('id', $message_id)->onlyTrashed()->first();
                $message->restore();
            }
        };
        return to_route('messages.index');
    }
}
