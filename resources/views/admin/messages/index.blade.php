@extends('layouts.app')

@section('content')
<div class="container">
  {{-- @dd($apartments) --}}
    <h1 class="text-center">Lista messaggi</h1>
    {{-- <a href="{{ route('admin.messages.trash') }}" class="btn btn-outline-primary my-3">Vai al cestino</a> --}}
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
            @foreach($apartments as $apartment)
              @if ($apartment->messages)
                @forelse ($apartment->messages as $message)
                  <tr>
                      <td>{{$apartment->title}}</td>
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
                    
                @empty
                    
                @endforelse 
              @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('modals')
<!-- Modal -->
{{-- @foreach($apartments as $apartment)
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
                          <form action="{{route('message.destroy', $message)}}" method="POST">
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
      @endforeach --}}
@endsection