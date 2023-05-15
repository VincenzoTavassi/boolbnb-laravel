@extends('layouts.app')

@section('scripts')
<script type='text/javascript' defer>
document.addEventListener("DOMContentLoaded", function() {
  let addressEl = document.getElementById('address');
  addressEl.addEventListener("focusout", () => {
    let addressValue = addressEl.value;
    // console.log(addressValue);
    delete axios.defaults.headers.common['X-Requested-With'] // Rimuovi il field per i CORS di TomTom
const response = axios.get(`https://api.tomtom.com/search/2/geocode/${addressValue}.json`, {
    headers: {
        },
          params: {
            key: 'tg2x9BLlB0yJ4y7Snk5XhTOsnakmpgUO',
            limit: 1,
          }}          
          ).then(response => {
            console.log(response.data.results[0])
        const coordinate = response.data.results[0].position;
        const address = response.data.results[0].address.freeformAddress;
        const longitudeEl = document.getElementById('longitude');
        const latitudeEl = document.getElementById('latitude');
        longitudeEl.value = parseFloat(coordinate.lon);
        latitudeEl.value = parseFloat(coordinate.lat);
        // console.log(longitudeEl.value)
        // console.log(latitudeEl.value-)

        console.log(latLonToTileZXY(latitudeEl.value, longitudeEl.value, 20))

        // console.log(address);
        // console.log(`Latitudine: ${coordinate.lat}, Longitudine: ${coordinate.lon}`);
          })})
        
        })
     
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
        @if($apartment->id)
            <h1>Modifica {{$apartment->title}}
            <form action="{{route('apartments.update', $apartment)}}" method="post" class="row" enctype="multipart/form-data">
            @method('put')
        @else
            <h1>Inserisci un appartamento</h1>
            <form action="{{route('apartments.store')}}" method="post" class="row" enctype="multipart/form-data">
        @endif
        @csrf
        </div>
        <div class="card-body container d-flex flex-column">
    <div class="row row-cols-2">
        <div class="col mb-3">
            <label for="title" class="form-label"><strong>Titolo descrittivo</strong></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Scrivi il titolo dell'appartamento"
            value="{{old('title', $apartment->title)}}">
                    @error('title')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
        </div>
        <div class="col mb-3">
            <label for="address" class="form-label"><strong>Indirizzo</strong></label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" placeholder="Indirizzo dell'appartamento"
            value="{{old('address', $apartment->address)}}">
            @error('address')
                <div class="invalid-feedback">
                {{ $message }}
                </div>
             @enderror
        </div>
    </div>
    <div class="row justify-content-between">
            <div class="col mb-3">
                <label for="price" class="form-label"><strong>Prezzo per notte</strong></label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                value="{{old('price', $apartment->price)}}">
                  @error('price')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
            <div class="col mb-3">
                <label for="square_meters" class="form-label"><strong>Superficie in mq</strong></label>
                <input type="number" class="form-control @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters"
                value="{{old('square_meters', $apartment->square_meters)}}">
                         @error('square_meters')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
            <div class="col mb-3">
                <label for="single_beds" class="form-label"><strong>Letti singoli</strong></label>
                <input type="number" class="form-control @error('single_beds') is-invalid @enderror" name="single_beds" id="single_beds"
                value="{{old('single_beds', $apartment->single_beds)}}">
                @error('single_beds')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
            <div class="col mb-3">
                <label for="double_beds" class="form-label"><strong>Letti Matrimoniali</strong></label>
                <input type="number" class="form-control @error('double_beds') is-invalid @enderror" name="double_beds" id="double_beds"
                value="{{old('double_beds', $apartment->double_beds)}}">
                @error('double_beds')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
             <div class="col mb-3">
                <label for="bathrooms" class="form-label"><strong>Bagni</strong></label>
                <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" 
                name="bathrooms" 
                id="bathrooms"
                value="{{old('bathrooms', $apartment->bathrooms)}}">
                 @error('bathrooms')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
            <div class="col mb-3">
                <label for="rooms" class="form-label"><strong>Camere</strong></label>
                <input type="number" class="form-control @error('rooms') is-invalid @enderror" name="rooms" id="rooms"
                value="{{old('rooms', $apartment->rooms)}}">
                @error('rooms')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            </div>
        </div>
        
<div class="row">
    <div class="col mb-3">
                <label for="image" class="form-label"><strong>Immagine</strong></label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image"
                value="{{old('image', $apartment->image)}}">
                @error('image')
                <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
            <div class="preview mt-2 w-50">
                <img src="{{$apartment->getImage()}}" class="w-75" id="image-preview" alt=""></div>
            </div>
            <div class="preview mt-2 w-50">
                <img src="{{"https://api.tomtom.com/map/1/staticimage?key=jnhg5VXkLxfsGIiOxCLf1aNxiWphz9YA&zoom=16&center=$apartment->longitude,$apartment->latitude&format=png&layer=basic&style=main&view=IN&language=en-GB"}}" 
                
                {{-- api.tomtom.com/map/1/tile/basic/main/7/67/45.png?key=y2wBH9hGczqacZNvYMXxr60Ovxx97ugX&tileSize=256&view=IL&language=it-IT --}}
                
                class="w-75" id="map" alt=""></div>
            </div>
 <div class="col form-check d-flex align-items-center">
     <input name="visible" class="form-check-input @error('visible') is-invalid @enderror" type="checkbox" value="{{old('visible',$apartment->visible)}}" id="visible[]" @if($apartment->visible) checked @endif>
     <label class="form-check-label ms-2" for="visible">
         Vuoi pubblicare l'appartamento?
     </label>
     @error('visible')
        <div class="invalid-feedback">
        {{ $message }}
        </div>
     @enderror
</div>
</div>
<div class="col mb-3">
    <label for="description" class="form-label">Descrizione</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="2">{{old('description', $apartment->description)}}</textarea>
    @error('description')
        <div class="invalid-feedback">
        {{ $message }}
        </div>
     @enderror
</div>
</div>

<div class="row align-items-center ms-2">
    @foreach ($services as $service)
    <div class="col-3">
            <input type="checkbox" 
            id="service-{{ $service->id }}" 
            name="services[]" 
            value="{{ $service->id }}"
            {{-- Se l'id del servizio è l'ultimo compilato dall'utente, oppure se è presente l'array
            dei servizi dai checked. Se non c'è l'array dei servizi, indicalo come vuoto --}}
            @if(in_array($service->id, old('services', $apartment_services ?? []))) checked @endif
            class="form-check-input @error('services') is-invalid @enderror" 
            @if (in_array($service->id, old('services', $apartment_services ?? []))) checked @endif>
            <label for="service-{{ $service->id }}">
            {{ $service->title }}
            </label><br>
        </div>
        @endforeach
    </div>

<div class="d-flex justify-content-center my-3">
    <input type="hidden" name="longitude" id="longitude" value="{{old('longitude', $apartment->longitude)}}">
    <input type="hidden" name="latitude" id="latitude" value="{{old('latitude', $apartment->latitude)}}">
    <button class="btn btn-primary w-25">@if($apartment->id)MODIFICA @else INVIA @endif</button>
</div>
</form>
</div>
 </div>
</div>

 <script>
        const imageInputEl = document.getElementById('image');
        const imagePreviewEl = document.getElementById('image-preview');
        const placehorder = imagePreviewEl.src;

        imageInputEl.addEventListener('change', () => {
            if (imageInputEl.files && imageInputEl.files[0]) {
                const reader = new FileReader();
                reader.readAsDataURL(imageInputEl.files[0]); 

                reader.onload = e => {
                    imagePreviewEl.src = e.target.result;
                }
            } else imagePreviewEl.src = placehorder;
        })
    </script>

    <script>
function latLonToTileZXY(lat, lon, zoomLevel) {
  const MIN_ZOOM_LEVEL = 0
  const MAX_ZOOM_LEVEL = 22
  const MIN_LAT = -85.051128779807
  const MAX_LAT = 85.051128779806
  const MIN_LON = -180.0
  const MAX_LON = 180.0

  if (
    zoomLevel == undefined ||
    isNaN(zoomLevel) ||
    zoomLevel < MIN_ZOOM_LEVEL ||
    zoomLevel > MAX_ZOOM_LEVEL
  ) {
    throw new Error(
      "Zoom level value is out of range [" +
        MIN_ZOOM_LEVEL.toString() +
        ", " +
        MAX_ZOOM_LEVEL.toString() +
        "]"
    )
  }

  if (lat == undefined || isNaN(lat) || lat < MIN_LAT || lat > MAX_LAT) {
    throw new Error(
      "Latitude value is out of range [" +
        MIN_LAT.toString() +
        ", " +
        MAX_LAT.toString() +
        "]"
    )
  }

  if (lon == undefined || isNaN(lon) || lon < MIN_LON || lon > MAX_LON) {
    throw new Error(
      "Longitude value is out of range [" +
        MIN_LON.toString() +
        ", " +
        MAX_LON.toString() +
        "]"
    )
  }

  let z = Math.trunc(zoomLevel)
  let xyTilesCount = Math.pow(2, z)
  let x = Math.trunc(Math.floor(((lon + 180.0) / 360.0) * xyTilesCount))
  let y = Math.trunc(
    Math.floor(
      ((1.0 -
        Math.log(
          Math.tan((lat * Math.PI) / 180.0) +
            1.0 / Math.cos((lat * Math.PI) / 180.0)
        ) /
          Math.PI) /
        2.0) *
        xyTilesCount
    )
  )

  return z.toString() + "/" + x.toString() + "/" + y.toString()
}
</script>
@endsection