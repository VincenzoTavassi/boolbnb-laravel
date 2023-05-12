@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col">
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
            <label for="title" class="form-label"><strong>Indirizzo</strong></label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Indirizzo dell'appartamento">
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
     <input name="visible" class="form-check-input" type="checkbox" value="" id="visible">
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
<div class="d-flex justify-content-center mb-3">
    <button class="btn btn-primary w-25">INVIA</button>
</div>
</form>
</div>
 </div>
</div>
@endsection