@extends('layouts.app')

@section('content')
<div class="container">
    
    <h2 class="fs-4 text-secondary my-4">
        {{ __('Dashboard') }}
    </h2>

    
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <p>Bentornato {{Auth::user()->name}}! Ecco qualche statistica di visualizzazione per i tuoi appartamenti nell'ultima settimana.</p>
                </div>
                <div class="card-body">

                
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Appartamento</th>
      @if($apartments)
      @foreach(array_keys($apartments[0]->date_views) as $date) 
      <th scope="col">@php 
        $format_date = date_create($date);
        echo date_format($format_date,"d/m/Y");
        @endphp</th>
      @endforeach 
      @endif
    </tr>
  </thead>
  <tbody>
    @if($apartments)
        @foreach($apartments as $apartment)
    <tr>
    <td>{{$apartment->title}}</td>
        @foreach($apartment->date_views as $view)
      <td class="text-center">{{$view}}</td>
      @endforeach
    </tr>
    @endforeach
    @endif
  </tbody>
</table>
</div>
            </div>
        </div>
    
</div>
@endsection
