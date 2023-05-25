@extends('layouts.app')

@section('scripts')
  <script src="https://js.braintreegateway.com/web/dropin/1.37.0/js/dropin.min.js"></script>
@endsection

@section('content')
<div class="container">
  <form id="payment-form" action="{{route('admin.payment.checkpay', $apartment)}}" method="post">
    @csrf
    <div class="d-flex flex-column align-items-center">
    <h1>Sponsorizza {{$apartment->title}}</h1>
    <p>Aumenta la visibilità sul nostro sito e mostra il tuo appartamento in homepage.</p>
  <label for="plan_selection">Seleziona il tuo piano:</label>
  <select class="form-select w-25" aria-label="Default select example" name="plan_id" id="plan_selection">
    <option selected>Scegli</option>
  <option value="1">Bronze - 24 ore - € 2.99 </option>
  <option value="2">Silver - 72 ore - € 5.99</option>
  <option value="3">Gold - 144 ore - € 9.99</option>
</select>

<div id="dropin-container" class="d-flex justify-content-center"></div>
<input type="submit" class="btn btn-outline-primary" />
<input type="hidden" id="nonce" name="payment_method_nonce"/>
<input type="hidden" id="apartment_id" name="apartment_id" value="{{$apartment->id}}">
</div>
</form>
</div>
  @endsection

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
  const apartment = document.querySelector('#apartment_id')
axios.get(`http://localhost:8000/admin/apartments/payment/gateway/${apartment.value}`).then((response) => {
let token = response.data;

braintree.dropin.create(
  { 
    container: document.getElementById('dropin-container'),
    authorization: token,
    locale: 'it_IT',
      card: {
    cardholderName: {
      required: true
    }
  }
  }, 
(error, dropinInstance) => {
  // Use `dropinInstance` here
  // Methods documented at https://braintree.github.io/braintree-web-drop-in/docs/current/Dropin.html
  if (error) console.error(error);
const form = document.getElementById('payment-form');

  form.addEventListener('submit', event => {
    event.preventDefault();
      dropinInstance.requestPaymentMethod((error, payload) => {
      if (error) console.error(error);
          document.getElementById('nonce').value = payload.nonce;
      form.submit();
   });
  });
});  

})})
</script>

</body>