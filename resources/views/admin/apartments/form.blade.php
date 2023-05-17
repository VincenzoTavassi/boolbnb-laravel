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
     text-align: center;
    }
    #image-preview {
      max-width: 100%;
      max-height: 300px;
    }
    .pub{
      /* padding-left: 12px !important; */
    }
    #search-results {
      position: absolute;
      top: 100%;
      width: 95%;
      height: 100px;
      background-color: rgba(255, 255, 255, 0.5);
      border: 1px solid gray;
      border-top: none;
      z-index: 99;
      padding-top: 5px;
      padding-bottom: 5px;
      cursor: pointer;
      overflow: auto;
    }
    .result:hover {
      background-color: lightskyblue;
      color: white;
    }
    .result {
      padding-left: 10px;
      padding-right: 10px;
    }
  </style>
@endsection

<!-- MAIN DEL FORMAT -->
@section('content')
  <div class="container">
    <div class="row justify-content-center">

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
        <div class="card container">
            <!-- EDIT -->
            @if($apartment->id)
              <form id="apartment-form" action="{{route('apartments.update', $apartment)}}" method="post" class="row needs-validation" novalidate enctype="multipart/form-data">
            @method('put')
            <!-- CREATE -->
            @else
              <form id="apartment-form" action="{{route('apartments.store')}}" method="post" class="row needs-validation" novalidate enctype="multipart/form-data">
            @endif
            @csrf
            
          <div class="card-header text-center">
            <h1>{{$apartment->id ? 'Modifica ' . $apartment->title : 'Inserisci un nuovo appartamento'}}</h1>
          </div>

          <div class="card-body container d-flex flex-column p-3">
            <div class="row row-cols-2">
              <div class="col mb-3">
                <label for="title" class="form-label"><strong>Titolo descrittivo</strong></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Scrivi il titolo dell'appartamento"
                  value="{{old('title', $apartment->title)}}" required>
                @error('title')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col d-flex align-items-center">
                <input name="visible" class="form-check-input m-0 @error('visible') is-invalid @enderror" type="checkbox" value="{{old('visible', $apartment->visible)}}" id="visible[]" @if($apartment->visible) checked @endif>
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
                  value="{{old('image')}}">
                @error('image')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="col position-relative">
                <div id="search-results" class="d-none">
                </div>
                    <label for="address" class="form-label"><strong>Indirizzo</strong></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" placeholder="Indirizzo dell'appartamento"
                      value="{{old('address', $apartment->address)}}" required>
                    @error('address')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>  
              </div>
            </div>

            <div class="container">
              <div class="row row-cols-2">
                <div class="col">
                  <div class="preview">
                    <img src="{{$apartment->getImage()}}" class="" id="image-preview" alt="">
                  </div>
                </div>
                <div class="col d-flex justify-content-center" >
                  <div id="map"></div>
                </div>
              </div>
            </div>
            
            <div class="container">
              <div class="row">
                <div class="col">
                  <label for="description" class="form-label"><strong>Descrizione</strong></label>
                  <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="2">{{old('description', $apartment->description)}}</textarea>
                  @error('description')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="valid-feedback">OK!</div>
              </div>
            </div>

            <div class="container">
            <div class="row justify-content-between my-2">
              <div class="col">
                <label for="price" class="form-label"><strong>Prezzo per notte</strong></label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                  value="{{old('price', $apartment->price)}}" required>
                @error('price')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col mb-3">
                <label for="square_meters" class="form-label"><strong>Superficie in mq</strong></label>
                <input type="number" class="form-control @error('square_meters') is-invalid @enderror" name="square_meters" id="square_meters"
                  value="{{old('square_meters', $apartment->square_meters)}}" required>
                @error('square_meters')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col mb-3">
                <label for="single_beds" class="form-label"><strong>Letti singoli</strong></label>
                <input type="number" class="form-control @error('single_beds') is-invalid @enderror" name="single_beds" id="single_beds"
                  value="{{old('single_beds', $apartment->single_beds)}}" required>
                @error('single_beds')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col mb-3">
                <label for="double_beds" class="form-label"><strong>Letti Matrimoniali</strong></label>
                <input type="number" class="form-control @error('double_beds') is-invalid @enderror" name="double_beds" id="double_beds"
                  value="{{old('double_beds', $apartment->double_beds)}}" required>
                @error('double_beds')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col mb-3">
                <label for="bathrooms" class="form-label"><strong>Bagni</strong></label>
                <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" 
                  name="bathrooms" 
                  id="bathrooms"
                  value="{{old('bathrooms', $apartment->bathrooms)}}" required>
                @error('bathrooms')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>

              <div class="col mb-3">
                <label for="rooms" class="form-label"><strong>Camere</strong></label>
                <input type="number" class="form-control @error('rooms') is-invalid @enderror" name="rooms" id="rooms"
                  value="{{old('rooms', $apartment->rooms)}}" required>
                @error('rooms')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
                <div class="valid-feedback">OK!</div>
              </div>
            </div>
         </div>
 
          <div class="container">
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
                    required
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
<script>
  // ELEMENTI HTML
  const addressEl = document.getElementById('address');
  const longitudeEl = document.getElementById('longitude');
  const latitudeEl = document.getElementById('latitude');
  const searchResultsEl = document.getElementById('search-results');
  const form = document.querySelector('#apartment-form')

  document.addEventListener("DOMContentLoaded", function() {
    // Se c'è già un indirizzo, invia alla funzione le coordinate
    if(addressEl.value) setMapCenter(latitudeEl.value, longitudeEl.value);
    else setMapCenter(41.862175027654935, 12.468740017291827, 3); // Altrimenti posizionati su Roma, zoom 3
  })   
</script>

<script>
// CUSTOM SCRIPT PER CERCARE GLI INDIRIZZI MENTRE SI DIGITA E AL CLICK AGGIORNA LA MAPPA
let addressInputEl = document.getElementById('address')
let possibleAddresses = [];

// Se l'utente clicca fuori dalla select, la rendiamo invalida
addressEl.addEventListener('blur', () => {
 searchResultsEl.classList.add('border-danger');
 searchResultsEl.classList.add('text-danger');
 addressEl.classList.add('is-invalid');
 form.classList.remove('was-validated');
})

addressEl.addEventListener('keyup', () => {
  // Rimuovo risultati precedenti
  const previousResults = document.querySelectorAll('.result')
  previousResults.forEach(result => result.remove())
  addressEl.classList.remove('is-invalid'); // Aggiorno validazione

  // Rimuovo le classi danger 
  searchResultsEl.classList.remove('border-danger');
  searchResultsEl.classList.remove('text-danger');
  // TODO: Se l'utente clicca al di fuori della select, la chiudiamo

  // Mostro il div con i risultati
  searchResultsEl.classList.remove('d-none')
  let addressValue = addressEl.value; // input dell'utente
  delete axios.defaults.headers.common['X-Requested-With'] // Rimuovi il field per i CORS di TomTom
    const response = axios.get(`https://api.tomtom.com/search/2/geocode/${addressValue}.json`, {
      headers: {
        },
      params: {
        key: 'jnhg5VXkLxfsGIiOxCLf1aNxiWphz9YA',
        limit: 5,
      }}).then(response => {
        possibleAddresses = []; // Reset
        response.data.results.forEach(risultato => {
          possibleAddresses.push({   // Inserisco indirizzo e coordinate nell'array
            indirizzo: risultato.address.freeformAddress,
            latitude: parseFloat(risultato.position.lat),
            longitude: parseFloat(risultato.position.lon),
          })
          
        })
        
      }).finally(()=> { // Ciclo per stampare indirizzi nel div
        for (let i = 0; i < possibleAddresses.length; i++) {
          let resultAddress = possibleAddresses[i];
          let resultDiv = document.createElement('div');
          resultDiv.classList.add("result")
          resultDiv.innerHTML += resultAddress.indirizzo;
          resultDiv.addEventListener('click', () => { 
            addressInputEl.value = resultAddress.indirizzo; // Inserisco nell'input l'indirizzo scelto dall'utente
            searchResultsEl.classList.add('d-none') // Chiudo il div
            // Aggiorno gli hidden fields
            longitudeEl.value = resultAddress.longitude;
            latitudeEl.value = resultAddress.latitude;
            // Invia coordinate alla funzione per aggiornare la mappa
            setMapCenter(latitudeEl.value, longitudeEl.value);
            // L'indirizzo ora è valido
            addressEl.classList.remove('is-invalid');

          })
          searchResultsEl.append(resultDiv);
        }
      })})

</script>

<script>
  // ## FUNZIONE TOM TOM MAP BUILDER ##
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

    let markerTitle = apartmentTitle.value ? apartmentTitle.value : 'Dove sarà il tuo appartamento?'
    var popup = new tt.Popup({ offset: popupOffsets }).setHTML(markerTitle)
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
<script>
    // !! ATTENZIONE MODIFICHE CUSTOM !! 
    //BOOTSTRAP FORM VALIDATION 
    (() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if(addressEl.classList.contains('is-invalid')) { // Modifica custom per la validazione indirizzo
        event.preventDefault()
        event.stopPropagation()
        form.classList.remove('was-validated') // Se l'indirizzo non è valido, non puoi proseguire
        window.scrollTo({ top: 0, behavior: 'smooth' }); 
        } else {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
  }}, false)
  })
})()
</script>
<script>
    // CUSTOM CHECKBOX VALIDATION, RICHIEDI ALMENO UN CHECKBOX
    const serviceCheckbox = document.querySelectorAll("[id^='service-']");

    function checkBoxValidation(checkBoxArray) {
    let checkedBoxes = false;
    checkBoxArray.forEach(service => {
      for (let i = 0; i < checkBoxArray.length; i++) {
          const checkBox = checkBoxArray[i]
          if (checkBox.checked == true) {
            checkedBoxes = true;
            break;
          } else checkedBoxes = false;
        }
          if(checkedBoxes) {
            checkBoxArray.forEach(service => { 
              service.required = false;
            })
          } else {
            checkBoxArray.forEach(service =>{
              service.required = true;
            })
          }
        
      })}

      checkBoxValidation(serviceCheckbox);

      serviceCheckbox.forEach(service => {
        service.addEventListener('change', () => {
            checkBoxValidation(serviceCheckbox);
        })
      })
    
    </script>

<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.18.0/maps/maps-web.min.js"></script>
@endsection