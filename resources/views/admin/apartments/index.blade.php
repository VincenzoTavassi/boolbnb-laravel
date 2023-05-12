@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="text-center">Lista Appartamenti</h1>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$apartments->links()}}
</div>
    @endsection