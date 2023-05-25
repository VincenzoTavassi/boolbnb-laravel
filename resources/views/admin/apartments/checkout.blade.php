@extends('layouts.app')

@section('scripts')
  <script src="https://js.braintreegateway.com/web/dropin/1.37.0/js/dropin.min.js"></script>
@endsection

@section('content')
<h1>Sponsorizza il tuo appartamento</h1>
<p>Aumenta la visibilità sul nostro sito e mostra il tuo appartamento in homepage.</p>
 <form id="payment-form" action="{{route('admin.payment.checkpay', $apartment)}}" method="post">
  @csrf
<label for="plan_selection">Seleziona il tuo piano:</label>
 <select class="form-select" aria-label="Default select example" name="plan_id" id="plan_selection">
  <option selected>Scegli</option>
  <option value="1">Bronze - 24 ore - € 2.99 </option>
  <option value="2">Silver - 72 ore - € 5.99</option>
  <option value="3">Gold - 144 ore - € 9.99</option>
</select>

    <div id="dropin-container"></div>
    <input type="submit" />
    <input type="hidden" id="nonce" name="payment_method_nonce"/>
  </form>
  @endsection

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script type="text/javascript">

axios.get('http://localhost:8000/admin/apartments/payment/gateway').then((response) => {
let token = response.data;

braintree.dropin.create(
  { 
    container: document.getElementById('dropin-container'),
    authorization: token, 
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





})
</script>

</body>