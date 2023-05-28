@extends('layouts.app')

@section('title')

@section('content')
{{-- @dd($apartment) --}}
<div class="container">
    <div class="my-4">
        <a class="btn btn-outline-primary me-3" href="{{ route('messages.index') }}">Torna alla lista</a>
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-{{$message->id}}" href="">Elimina il messaggio</button>
    </div>
    
    <div class="card">
        <div class="card-body">
            <h3>
                <a class="apartmentLinks me-2" href="{{route('apartments.show', $apartment)}}">{{$apartment->title}}</a>
            </h3>
            <hr>
            <div class="row">
                <div class="col">
                  <p class="mb-0"><b>Mittente: </b> {{$message->email}} </p>
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

@section('modals')
<!-- Modal -->
    <div class="modal fade" id="delete-{{$message->id}}" tabindex="-1" >
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Elimina il messaggio inviato da {{$message->email}}?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"button>
            </div>
            <div class="modal-body">
                Attenzione! Stai spostando questo messaggio nel cestino<br>
                Sei sicuro di volerlo spostare?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="{{route('admin.messages.destroy', [$apartment, $message])}}" method="POST">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </form>
            </div>
            </div>
        </div>
    </div>     
@endsection