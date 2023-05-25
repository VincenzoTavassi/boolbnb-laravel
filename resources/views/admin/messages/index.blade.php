@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Lista messaggi</h1>
    <div class="d-flex justify-content-between">
      <a href="{{ route('admin.messages.trash') }}" class="btn btn-outline-danger my-3">Vai al cestino</a>
      <form action="{{ route('messages.index') }}" method="GET" role="search" class="d-flex my-3">
        <a href="{{ route('messages.index') }}">
          <button class="btn btn-outline-danger" type="button" title="Refresh page">
            <i class="bi bi-arrow-repeat"></i>
          </button>
        </a>
        <input type="text" class="form-control mx-2" name="term" placeholder="Search by Apartment" id="term">
        <button class="btn btn-outline-success" type="submit" title="Search messages">
          <i class="bi bi-search"></i>
        </button>
      </form>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Apartment</th>
                <th scope="col">Address</th>
                <th scope="col">Sent by</th>
                <th scope="col">Message preview</th>
                <th scope="col">Received on</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
          @if (count($messages) > 0)
            @foreach ($messages as $message)
                <tr>
                    <td>
                      <a class="apartmentLinks me-2" href="{{route('apartments.show', $message->apartment)}}">{{$message->apartment->title}}</a>
                    </td>
                    <td>{{$message->apartment->address}}</td>
                    <td>{{$message->email}}</td>
                    <td>{!! Str::limit($message->text, 15) !!}</td>
                    <td>{{$message->created_at->format(' jS F Y H:i')}}</td>
                    <td class="d-flex">
                        <a class="me-2" href="{{route('admin.messages.show', [$message->apartment, $message])}}">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        <button class="trash bi bi-trash-fill text-danger" data-bs-toggle="modal" data-bs-target="#delete-{{$message->id}}" href=""></button>
                    </td>
                </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="text-center">Non ci sono messaggi.</td>
            </tr>
          @endif
        </tbody>
    </table>
</div>
@endsection

@section('modals')
<!-- Modal -->
      @forelse ($messages as $message)
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
                  <form action="{{route('admin.messages.destroy', [$message->apartment, $message])}}" method="POST">
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
@endsection