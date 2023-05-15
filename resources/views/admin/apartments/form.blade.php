@extends('layouts.app')

@section('scripts')
<script type='text/javascript'>
document.addEventListener("DOMContentLoaded", function() {
  let addressEl = document.getElementById('address');
  addressEl.addEventListener("focusout", () => {
    let addressValue = addressEl.value;
    // console.log(addressValue);
    
    async function searchLocation() { // Corrected function definition
      try {
        const response = await axios.get(`https://api.tomtom.com/search/2/geocode/${addressValue}.json`, {
          params: {
            key: 'tg2x9BLlB0yJ4y7Snk5XhTOsnakmpgUO',
            limit: 1,
          },
        });
        const coordinate = response.data.results[0].position;
        console.log(`Latitudine: ${coordinate.lat}, Longitudine: ${coordinate.lon}`);
        // Here you can use the coordinates to make a request to your backend
        // You can also return the coordinates if needed
        return coordinate;
      } catch (error) {
        console.error(error);
      }
    }

    // Call the searchLocation function
    searchLocation();
  });
});
</script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">

@if (session('message'))
    <div class="alert alert-success my-3">
        {{ session('message') }}
    </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
    <h4>Correggi i seguenti errori per proseguire: </h4>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

    <div class="col p-0">
        <div class="card">
            <div class="card-header text-center">
                <h1>Inserisci un appartamento</h1>
            </div>
    <form action="{{route('apartments.store')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body container d-flex flex-column">
    <div class="row row-cols-2">
        <div class="col mb-3">
            <label for="title" class="form-label"><strong>Titolo descrittivo</strong></label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Scrivi il titolo dell'appartamento">
        </div>
        <div class="col mb-3">
            <label for="address" class="form-label"><strong>Indirizzo</strong></label>
            <input type="text" class="form-control" name="address" id="address" placeholder="Indirizzo dell'appartamento">
        </div>
    </div>
    <div class="row justify-content-between">
            <div class="col mb-3">
                <label for="price" class="form-label"><strong>Prezzo per notte</strong></label>
                <input type="number" class="form-control" name="price" id="price">
            </div>
            <div class="col mb-3">
                <label for="square_meters" class="form-label"><strong>Superficie in mq</strong></label>
                <input type="number" class="form-control" name="square_meters" id="square_meters">
            </div>
            <div class="col mb-3">
                <label for="single_beds" class="form-label"><strong>Letti singoli</strong></label>
                <input type="number" class="form-control" name="single_beds" id="single_beds">
            </div>
            <div class="col mb-3">
                <label for="double_beds" class="form-label"><strong>Letti Matrimoniali</strong></label>
                <input type="number" class="form-control" name="double_beds" id="double_beds">
            </div>
             <div class="col mb-3">
                <label for="bathrooms" class="form-label"><strong>Bagni</strong></label>
                <input type="number" class="form-control" name="bathrooms" id="bathrooms">
            </div>
            <div class="col mb-3">
                <label for="rooms" class="form-label"><strong>Camere</strong></label>
                <input type="number" class="form-control" name="rooms" id="rooms">
            </div>
        </div>
        
<div class="row">
    <div class="col mb-3">
                <label for="image" class="form-label"><strong>URL Immagine</strong></label>
                <input type="text" class="form-control" name="image" id="image">
            </div>
 <div class="col form-check d-flex align-items-center">
     <input name="visible" class="form-check-input" type="checkbox" value="1" id="visible[]">
     <label class="form-check-label ms-2" for="visible">
         Vuoi pubblicare l'appartamento?
     </label>
</div>
</div>
<div class="col mb-3">
    <label for="description" class="form-label">Descrizione</label>
    <textarea name="description" class="form-control" id="description" rows="2"></textarea>
</div>
</div>

<div class="row align-items-center ms-2">
    @foreach ($services as $service)
    <div class="col-3">
            <input type="checkbox" 
            id="service-{{ $service->id }}" 
            name="services[]" 
            value="{{ $service->id }}" 
            class="form-check-input" @if (in_array($service->id, old('services', $apartment_services ?? []))) checked @endif>
            <label for="service-{{ $service->id }}">
            {{ $service->title }}
            </label><br>
        </div>
        @endforeach
</div>

<div class="d-flex justify-content-center my-3">
    <button class="btn btn-primary w-25">INVIA</button>
</div>
</form>
</div>
 </div>
</div>
@endsection