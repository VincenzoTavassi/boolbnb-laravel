@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Lista messaggi dell'{{$apartment->title}}</h1>
    <div>
      <a href="{{ route('messages.index') }}" class="btn btn-outline-success my-3 me-2">Vedi tutti i messaggi</a>
      <a href="{{ route('admin.messages.trash') }}" class="btn btn-outline-danger my-3">Vai al cestino</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Apartment</th>
                <th scope="col">Address</th>
                <th scope="col">Sent by</th>
                <th scope="col">Message preview</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
              @if ($apartment->messages)
                @foreach ($apartment->messages as $message)
                    <tr>
                        <td>
                          <a class="apartmentLinks me-2" href="{{route('apartments.show', $apartment)}}">{{$apartment->title}}</a>
                        </td>
                        <td>{{$apartment->address}}</td>
                        <td>{{$message->email}}</td>
                        <td>{!! Str::limit($message->text, 15) !!}</td>
                        <td class="d-flex">
                            <a class="me-2" href="{{route('admin.messages.show', [$apartment, $message])}}">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <button class="trash bi bi-trash-fill text-danger" data-bs-toggle="modal" data-bs-target="#delete-{{$message->id}}" href=""></button>
                        </td>
                    </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="5">There's no message</td>
                </tr
              @endif
        </tbody>
    </table>
</div>
@endsection

@section('modals')
<!-- Modal -->
    @if ($apartment->messages)
      @forelse ($apartment->messages as $message)
        <div class="modal fade" id="delete-{{$message->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina il messaggio inviato da {{$message->email}}?</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
      @empty
                    
      @endforelse 
    @endif
@endsection