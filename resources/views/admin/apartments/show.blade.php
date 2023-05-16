@extends('layouts.app')

@section('title', $apartment->title)

@section('content')
<div class="container">
    <div class="my-4">
        <a class="btn btn-primary me-2" href="{{ route('apartments.index') }}">Torna alla lista</a>
        <a class="btn btn-primary" href="{{ route('apartments.edit', $apartment) }}">Modifica annuncio</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h6>Questo appartamento {{($apartment->visible) ? 'è' : 'non'}} pubblicato</h6>
                    <hr>
                    <img src="{{ $apartment->getImage() }}" class="w-100" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h5 class="me-2">Indirizzo: </h5>
                    {{$apartment->address}}
                </div>
                <div class="col">
                    <h5 class="me-2">Prezzo per notte: </h5>
                    {{$apartment->price}} €
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
                <h5>Ulteriori servizi:</h5>
                <div class="row">
                    @foreach($apartment->services as $service)
                        <div class="col">
                            {{ $service->title }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection