<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show($apartment)
    {
        return view('admin.payment.sponsoredPayment', compact('apartment'));
    }

    // public function process(Request $request)
    // {
    //     $payload = $request->input('payload', false);
    //     $nonce = $payload['nonce'];

    //     $status = Braintree_Transaction::sale([
    //     'amount' => '10.00',
    //     'paymentMethodNonce' => $nonce,
    //     'options' => [
    //         'submitForSettlement' => True
    //     ]
    //     ]);

    //     return response()->json($status);
    // }




























    public function store(Request $request)
    {
        if (!Auth::user()) return response()->json(['error' => 'Utente non loggato'], 403);
        $user = Auth::user();
        $data = $request->all();
        $apartment_id = $data['apartment_id'];
        $apartment = Apartment::findOrFail($apartment_id);
        $plan_id = $data['plan_id'];
        $plan = Plan::findOrFail($plan_id);
        if ($apartment->user->id != $user->id) return back()->with('danger', 'Non sei autorizzato a sponsorizzare questo appartamento');
        $current_date = Carbon::now('Europe/Rome');
        $has_plan = $apartment->plans()->where('end_date', '>', $current_date)->orderBy('end_date', 'asc')->first();
        if ($has_plan) return to_route('apartments.show', compact('apartment'))->with('danger', "E' già presente un piano di sponsorizzazione attiva per questo appartamento");
        $apartment->plans()->attach($plan_id, [
            'start_date' => $current_date->toDateTimeString(),
            'created_at' => $current_date->toDateTimeString(),
            'end_date' => $current_date->addHours($plan->length),
        ]);
        $current_date_now = Carbon::now('Europe/Rome');
        $active_plan = $apartment->plans()->where('end_date', '>', $current_date_now)->orderBy('end_date', 'asc')->first();

        return to_route('apartments.show', compact('apartment'))->with('message', 'Appartamento sponsorizzato con successo per ' . $active_plan->length . ' ore');
    }
}