@extends('layouts.app')

@section('title', /*$apartment->title*/)

@section('content')
<div class="container">
    <div class="my-4">
        <a class="btn btn-outline-primary me-3" href="{{ route('apartments.index') }}">Torna alla lista</a>
        <a class="btn btn-outline-primary me-3" href="{{ route('apartments.edit', $apartment) }}">Modifica annuncio</a>
        <a class="btn btn-outline-success" href="{{ route('admin.messages.listByApartment', $apartment) }}">Vai ai messaggi dell'annuncio</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h3>{{ $apartment->title }}</h3>
                @if(!$apartment->current_sponsored)
                <a class="btn btn-outline-success" href="{{ route('admin.payment.show', $apartment) }}">Vuoi sponsorizzare l'appartamento?</a>                    
                @else
                <div class="text-center text-success">
                    <p>Complimenti! Il tuo appartamento ha un piano attivo!</p>
                    <p>Restano <strong>{{$apartment->current_sponsored['time_left']}} ore</strong> alla fine del tuo piano <strong>{{$apartment->current_sponsored['plan']}}</strong>.</p>
                </div>
                @endif
            </div>
            <hr>
            <div class="row">
                <div class="col-4">
                    <h6 class="mb-5">Questo appartamento {{($apartment->visible) ? 'è' : 'non è'}} pubblicato</h6>
                    <hr class="w-50">
                    <div class="mb-5">
                        <h5 class="">Indirizzo: </h5>
                        {{$apartment->address}}
                    </div>
                    <hr class="w-50">
                    <h5 class="me-2">Prezzo per notte: </h5>
                    {{$apartment->price}} €
                </div>
                <div class="col-8 text-center">
                    <img src="{{ $apartment->getImage() }}" class="w-50" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    
                </div>
                <div class="col">
                    
                </div>
            </div>
            <hr>
            <div class="mt-2">
                <h5>Descrizione:</h5>
                <p>{{$apartment->description}}</p>
            </div>
            <hr>
            <div>
                <h5>Caratteristiche: </h5>
                <ul>
                    <li>Metratura: {{$apartment->square_meters}} mq</li>
                    <li>Stanze: {{$apartment->rooms}}</li>
                    <li>Bagni: {{$apartment->bathrooms}}</li>
                    <li>Letti doppi: {{$apartment->double_beds}}</li>
                    <li>Letti singoli: {{$apartment->single_beds}}</li>
                </ul>  
            </div>
            <hr>
            <div>
                <h5 class="mb-3">Ulteriori servizi:</h5>
                <div class="row">
                    @foreach($apartment->services as $service)
                        <div class="col-2 mb-3">
                            <i class="bg-secondary text-light p-1 bi {!! $service->icon !!}"></i>
                            <span>{{ $service->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection