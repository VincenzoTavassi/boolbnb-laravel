@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Lista Appartamenti</h1>
    <a href="{{ route('apartments.create') }}" class="btn btn-outline-primary my-3 me-3">Crea annuncio</a>
    <a href="{{ route('admin.apartments.trash') }}" class="btn btn-outline-danger my-3">Vai al cestino</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Immagine</th>
                <th scope="col">Promozioni</th>
                <th scope="col">Title</th>
                <th scope="col">Address</th>
                <th scope="col">Price</th>
                <th scope="col">Last Update</th>
                <th scope="col">Created</th>
                <th scope="col">Visible</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($apartments) > 0)
                @foreach($apartments as $apartment)
                <tr class="align-middle">
                    <th scope="row"><img src="{{$apartment->image}}" alt="{{$apartment->title}}" height="100px"></th>
                    <td class="text-success text-center">
                        <span>{{$apartment->current_sponsored ? $apartment->current_sponsored['plan'] : ''}}</span><br>
                        <span>{{$apartment->current_sponsored ? '(-' . $apartment->current_sponsored['time_left'] . ' ore)' : ''}}
                            </span></td>
                    <td>{{$apartment->title}}</td>
                    <td>{{$apartment->address}}</td>
                    <td>{{$apartment->price}}</td>
                    <td>{{$apartment->updated_at}}</td>
                    <td>{{$apartment->created_at}}</td> 
                    <td>{{($apartment->visible) ? 'Yes' : 'No'}}</td>
                    <td>
                        <div class="d-flex">

                            <a class="me-2" href="{{route('apartments.edit', $apartment)}}">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a class="me-2" href="{{route('apartments.show', $apartment)}}">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <button class="trash bi bi-trash-fill text-danger" data-bs-toggle="modal" data-bs-target="#delete-{{$apartment->id}}" href=""></button>
                        </td>
                    </div>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">Non ci sono appartamenti.</td>
                </tr>
            @endif
        </tbody>
    </table>
    {{$apartments->links()}}
</div>
@endsection

@section('modals')
<!-- Modal -->
@foreach($apartments as $apartment)
<div class="modal fade" id="delete-{{$apartment->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina {{$apartment->title}}?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Attenzione! Stai spostando questo appartamento nel cestino<br>
          Sei sicuro di volerlo spostare?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <form action="{{route('apartments.destroy', $apartment)}}" method="POST">
                @method('delete')
                @csrf
                <button type="submit" class="btn btn-danger">Elimina</button>
            </form>
        </div>
      </div>
    </div>
</div>     
@endforeach
@endsection