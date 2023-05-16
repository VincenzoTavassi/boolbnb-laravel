@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Lista Appartamenti</h1>
    <a href="{{ route('apartments.create') }}" class="btn btn-primary my-3">Crea annuncio</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">id</th>
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
            @foreach($apartments as $apartment)
            <tr>
                <th scope="row">{{$apartment->id}}</th>
                <td>{{$apartment->title}}</td>
                <td>{{$apartment->address}}</td>
                <td>{{$apartment->price}}</td>
                <td>{{$apartment->updated_at}}</td>
                <td>{{$apartment->created_at}}</td> 
                <td>{{($apartment->visible) ? 'Yes' : 'No'}}</td>
                <td class="d-flex">
                    <a class="me-2" href="{{route('apartments.edit', $apartment)}}">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <a class="me-2" href="{{route('apartments.show', $apartment)}}">
                        <i class="bi bi-eye-fill"></i>
                    </a>
                    <button class="trash bi bi-trash-fill text-danger" data-bs-toggle="modal" data-bs-target="#delete-{{$apartment->id}}" href=""></button>
                </td>
            </tr>
            @endforeach
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
          Attenzione! Stai eliminando questo appartamento<br>
          Sei sicuro di volerlo eliminare?
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