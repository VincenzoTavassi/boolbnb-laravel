@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Lista cestino</h1>
    <a href="{{ route('apartments.index') }}" class="btn btn-outline-primary my-3">Torna alla lista</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Adress</th>
                <th scope="col">Price</th>
                <th scope="col">
                    Creazione
                    <a href="created_at"></a>
                </th>
                <th scope="col">
                    Ultima modifica
                    <a href="{{'updated_at' != 'DESC'}}"></a>
                </th>
                <th scope="col">
                    Data cancellazione
                    <a href="deleted_at"></a>
                </th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apartments as $apartment)
            <tr>
                <td>{{$apartment->title}}</td>
                <td>{{$apartment->address}}</td>
                <td>{{$apartment->price}}</td>
                <td>{{$apartment->created_at}}</td> 
                <td>{{$apartment->updated_at}}</td>
                <td>{{($apartment->deleted_at)}}</td>
                <td class="d-flex">
                    <button class="trash bi bi-reply-fill text-success" data-bs-toggle="modal" data-bs-target="#restore-{{$apartment->id}}" href=""></button>
                    <button class="trash bi bi-trash-fill text-danger ms-3" data-bs-toggle="modal" data-bs-target="#delete-{{$apartment->id}}" href=""></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{--{{$apartments->links()}}--}}
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
          Attenzione! Stai eliminando questo appartamento.<br>
          Sei sicuro di volerlo eliminare?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <form action="{{route('admin.apartments.forcedelete', $apartment)}}" method="POST">
                @method('delete')
                @csrf
                <button type="submit" class="btn btn-danger">Elimina</button>
            </form>
        </div>
      </div>
    </div>
</div> 

<div class="modal fade" id="restore-{{$apartment->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ripristina {{$apartment->title}}?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Attenzione! Stai ripristinando questo appartamento.<br>
          Sei sicuro di volerlo ripristinare?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <form action="{{route('admin.apartments.restore', $apartment->id)}}" method="POST">
                @method('put')
                @csrf
                <button type="submit" class="btn btn-success">Ripristina</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endforeach
@endsection