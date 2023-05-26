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

@foreach($apartments as $apartment)
<div class="my-3">
  <canvas id="{{$apartment->id}}"></canvas>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
  const ctx = document.getElementById('myChart');
  
  axios.get('http://localhost:8000/admin/views/json').then((response) => {
    const apartments = response.data;
    apartments.forEach(apartment => {
      let labels = [];
      let data = [];
      const chart = document.getElementById(apartment.id);
      for(const date in apartment.date_views) {
        labels.push(date)
        data.push(apartment.date_views[date]);
      }

      const chart_one = new Chart(chart, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: '# di visite uniche per ' + apartment.title,
            data,
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      

    })
  })


  
</script>
@endsection
