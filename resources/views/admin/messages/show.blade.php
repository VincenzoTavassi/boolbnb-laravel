@extends('layouts.app')

@section('title')

@section('content')
<div class="container">
    <div class="my-4">
        <a class="btn btn-outline-primary me-3" href="{{ route('messages.index') }}">Torna alla lista</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <h3>{{ $apartment->title }}</h3>
            <hr>
            <div class="row">
                <div class="col">
                  <p><b>Mittente: </b> {{$message->email}} </p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                  @if ($message->name)
                      <p><b>Nome mittente:</b> {{$message->name}} </p>
                  @endif
                  <p><b>Messaggio: </b><br>
                     {{$message->text}} 
                  </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection