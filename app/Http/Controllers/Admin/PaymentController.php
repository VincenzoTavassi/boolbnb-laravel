<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Plan;
use Braintree;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Apartment $apartment)
    {
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return to_route('apartments.index')
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }
        return view('admin.apartments.checkout', compact('apartment'));
    }

    public function gateway($id)
    {
        $apartment = Apartment::findOrFail($id);
        $user_id = Auth::id();
        if ($user_id != $apartment->user_id) {
            return to_route('apartments.index')
                ->with('danger', 'Non sei autorizzato a vedere questo elemento');
        }

        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $clientToken = $gateway->clientToken()->generate();
        return response()->json($clientToken);
    }

    public function check_pay(Request $request, Apartment $apartment)
    {
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $data = $request->all();
        $nonceFromTheClient = $data['payment_method_nonce'];
        $user = Auth::user();
        if (!Auth::user()) return response()->json(['error' => 'Utente non loggato'], 403);
        $plan_id = $data['plan_id'];
        $plan = Plan::findOrFail($plan_id);
        if ($apartment->user->id != $user->id) return to_route('apartments.index')->with('danger', 'Non sei autorizzato a sponsorizzare questo appartamento');
        $current_date = Carbon::now('Europe/Rome');
        $has_plan = $apartment->plans()->where('end_date', '>', $current_date)->orderBy('end_date', 'asc')->first();
        if ($has_plan) return to_route('apartments.show', compact('apartment'))->with('danger', "E' già presente un piano di sponsorizzazione attiva per questo appartamento");

        $result = $gateway->transaction()->sale([
            'amount' => $plan->price,
            'paymentMethodNonce' => $nonceFromTheClient,
            'deviceData' => '',
            'options' => [
                'submitForSettlement' => True
            ]
        ]);
        if ($result->success) {
            $apartment->updated_at = $current_date->toDateTimeString();
            $apartment->save();
            $apartment->plans()->attach($plan_id, [
                'start_date' => $current_date->toDateTimeString(),
                'created_at' => $current_date->toDateTimeString(),
                'end_date' => $current_date->addHours($plan->length),
            ]);
            return to_route('apartments.show', compact('apartment'))->with('message', 'Sponsorizzazione attivata con successo');
        } else return to_route('apartments.show', compact('apartment'))->with('danger', 'Transazione fallita. Riprova con un metodo di pagamento diverso oppure controlla i dati inseriti.');
    }
}
