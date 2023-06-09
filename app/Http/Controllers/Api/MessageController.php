<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Apartment;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Apartment::where('id', '=', $request->apartment_id)->first()->user_id;
        $request['user_id'] = $user_id;
        $data = $this->validation($request->all());

        $message = new Message();
        $message->fill($data);
        $message->save();
        return 'OK';
    }

    ## VALIDATION
    private function validation($data)
    {

        return Validator::make(

            $data,
            [
                'email' => 'required|max:100',
                'name' => 'nullable|max:50',
                'text' => 'required',
                'apartment_id' => 'required|numeric',
                'user_id' => 'required|numeric'
            ],
        )->validate();
    }
}
