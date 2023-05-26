@extends('layouts.app')
@section('content')
  <div class="container">
    <h2 class="fs-4 text-secondary my-4">
        {{ __('Benvenuto!') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
              @if (Auth::user())
                <div class="card-header">Ciao {{Auth::user()->name}}!</div>

                <div class="card-body">
                    {{ __('Accedi alla tua lista appartamenti o creane uno dai link sopra.') }}
                </div>
              @else
                <div class="card-header">{{ __('Guest User') }}</div>

                <div class="card-body">
                    {{ __('Benvenuto! Effettua il login per vedere i tuoi appartamenti o registrati.') }}
                </div>
              @endif
            </div>
        </div>
    </div>
</div>
@endsection
