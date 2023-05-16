@extends('layouts.app')

@section('scripts')
  <link
    rel="stylesheet"
    type="text/css"
    href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/maps/maps.css"
  />

  <style>
    #map {
      height: 300px;
      width: 100%;
      }
    .preview{
      min-height: 300px;
      min-width: 300px;
    }
    .pub{
      padding-left: 12px !important;
    }
  </style>
@endsection

<!-- MAIN DEL FORMAT -->
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
      
      <!-- CONTAINER DEL FORMAT -->
      <div class="col">
        <div class="card">
          <div class="card-header text-center">

            <!-- EDIT -->
            @if($apartment->id)
              <h1>Modifica {{$apartment->title}}
              <form action="{{route('apartments.update', $apartment)}}" method="post" class="row" enctype="multipart/form-data">
            @method('put')
            <!-- CREATE -->
            @else
              <h1>Inserisci un appartamento</h1>
              <form action="{{route('apartments.store')}}" method="post" class="row" enctype="multipart/form-data">
            @endif
            @csrf
          </div>

          <div class="card-body container d-flex flex-column p-3">
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

              <div class="col form-check d-flex align-items-center pub">
                <input name="visible" class="form-check-input m-0 @error('visible') is-invalid @enderror" type="checkbox" value="{{old('visible',$apartment->visible)}}" id="visible[]" @if($apartment->visible) checked @endif>
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

            <div class="row row-cols-2">
              <div class="col">
                <label for="image" class="form-label"><strong>Immagine</strong></label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image"
                  value="{{old('image', $apartment->image)}}">
                @error('image')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="col">
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
            </div>

            <div class="row row-cols-2 justify-content-between">
              <div class="col">
                <div class="ms-3 preview">
                  <img src="{{$apartment->getImage()}}" class="" style="width: 90%" id="image-preview" alt="">
                </div>
              </div>
            <div class="col" >
                <div id="map" style="width: 90%"></div>
              </div>
            </div>
            
            <div class="row">
              <div class="col m-3">
                <label for="description" class="form-label"><strong>Descrizione</strong></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="2">{{old('description', $apartment->description)}}</textarea>
                @error('description')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>

            <div class="row justify-content-between m-2">
              <div class="col">
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
            
            <div class="row align-items-center ms-2 mb-3">
              <div class="text-center">
                <h4>Aggiungi servizi</h4>
              </div>
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
                    @if (in_array($service->id, old('services', $apartment_services ?? []))) checked @endif
                  >
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
          </div>


        </form>
      </div>
    </div>
  </div>
  
<!-- SCRIPT -->
<script type='text/javascript' defer>
  // ELEMENTI HTML
  const addressEl = document.getElementById('address');
  const longitudeEl = document.getElementById('longitude');
  const latitudeEl = document.getElementById('latitude');

  document.addEventListener("DOMContentLoaded", function() {
    // Se c'è già un indirizzo, invia alla funzione le coordinate
    if(addressEl.value) setMapCenter(latitudeEl.value, longitudeEl.value);
    else setMapCenter(41.862175027654935, 12.468740017291827, 3);

    // Se l'indirizzo cambia, esegui la funzione con le nuove coordinate
    addressEl.addEventListener("focusout", () => {
      let addressValue = addressEl.value;
    delete axios.defaults.headers.common['X-Requested-With'] // Rimuovi il field per i CORS di TomTom
    const response = axios.get(`https://api.tomtom.com/search/2/geocode/${addressValue}.json`, {
      headers: {
        },
      params: {
        key: 'tg2x9BLlB0yJ4y7Snk5XhTOsnakmpgUO',
        limit: 1,
      }}          
      ).then(response => {
        const coordinate = response.data.results[0].position;
        const address = response.data.results[0].address.freeformAddress;
        // Parse a float delle coordinate
        longitudeEl.value = parseFloat(coordinate.lon);
        latitudeEl.value = parseFloat(coordinate.lat);
        // Invio le coordinate alla funzione per la mappa
        setMapCenter(latitudeEl.value, longitudeEl.value);
    })})
  })   
</script>

<script>
  // ## TOM TOM MAP BUILDER ##
  function setMapCenter(lat, lon, zoomlevel = null) {
    if(!zoomlevel) zoomlevel = 16;
    const apartmentTitle = document.getElementById('title');
    const apartmentCoordinates = [lon, lat]
    var map = tt.map({
      container: "map",
      key: "tg2x9BLlB0yJ4y7Snk5XhTOsnakmpgUO",
      center: apartmentCoordinates,
      zoom: zoomlevel,
    })
  
    var marker = new tt.Marker().setLngLat(apartmentCoordinates).addTo(map)

    var popupOffsets = {
      top: [0, 0],
      bottom: [0, -40],
      "bottom-right": [0, -70],
      "bottom-left": [0, -70],
      left: [25, -35],
      right: [-25, -35],
    }

    var popup = new tt.Popup({ offset: popupOffsets }).setHTML(apartmentTitle.value)
    marker.setPopup(popup).togglePopup()
  }
</script>

<script>
  // Script che aggiorna la vista dell'immagine da caricare quando viene selezionata
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

<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/maps/maps-web.min.js"></script>
@endsection