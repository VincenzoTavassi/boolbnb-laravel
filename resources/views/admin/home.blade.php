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

<div class="my-3 d-flex justify-content-center flex-column align-items-center">
<label for="interval" class="mb-2">Oppure seleziona un intervallo per i grafici:</label>
<select class="form-select w-25" id="interval" aria-label="Default select example">
  <option selected value="7">Ultima settimana</option>
  <option value="30">Ultimo mese</option>
  <option value="60">Ultimi 2 mesi</option>
  <option value="90">Ultimi 3 mesi</option>
  <option value="180">Ultimi 6 mesi</option>
  <option value="360">Ultimo anno</option>
</select>
</div>

@foreach($apartments as $apartment)
<div class="my-3">
  <canvas id="{{$apartment->id}}"></canvas>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
const intervalSelect = document.getElementById('interval');
let chartInstances = [];
let days = 6;
createGraph(days)

intervalSelect.addEventListener('change', () => {
  days = intervalSelect.value;
   resetChartInstances()
   createGraph(days)
  })

function createGraph(days) {
  axios.get(`http://localhost:8000/admin/views/${days}/json`).then((response) => {
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
      })
      
      chartInstances.push(chart_one);


    })
  })}

function resetChartInstances() {
  // Itera sulle istanze di Chart e distruggile
  chartInstances.forEach(chartInstance => {
    chartInstance.destroy();
  });

  // Svuota l'array delle istanze di Chart
  chartInstances = []; 
}

  
</script>
@endsection
