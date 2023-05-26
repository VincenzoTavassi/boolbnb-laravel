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
    public function index(Request $request)
    {
        $user_id = Auth::id();
        if ($request->term) {
            $apartment_ids = Apartment::where('user_id', '=', $user_id)
                ->where('title', 'LIKE', '%'.$request->term.'%')
                // ->get();
                ->pluck('id');
            $messages = [];    
            foreach ($apartment_ids as $id) {
                $messages[] = Message::where('apartment_id', '=', $id)
                ->with('apartment')
                ->orderBy('created_at', 'ASC')
                ->get();
            }
        } else {
            $messages[] = Message::where('user_id', '=', $user_id)
                ->with('apartment')
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        // dd($messages);

        return view('admin.messages.index', compact('messages', 'user_id'));
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
        $messages = Message::onlyTrashed()
            ->where('user_id', '=', $user_id)
            ->with('apartment')
            ->orderBy('deleted_at', 'ASC')
            ->get();
        return view('admin.messages.trash', compact('messages'));
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
