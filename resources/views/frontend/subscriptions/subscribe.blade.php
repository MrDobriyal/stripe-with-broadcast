@extends('frontend.layouts.app')

@section('content')
<h3>Subscribe to {{ ucfirst($plan) }} Plan</h3>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form id="payment-form" method="POST" action="{{ route('subscribe.store') }}">
    @csrf

    <input type="hidden" name="plan" value="{{ $plan }}">
    <input type="hidden" name="payment_method" id="payment_method">

    <input type="text"
           id="card-holder-name"
           class="form-control mb-3"
           placeholder="Card holder name"
           required>

    <div id="card-element" class="mb-3"></div>
    <div id="card-errors" class="text-danger"></div>

    <button class="btn btn-primary mt-3">
        Subscribe
    </button>
</form>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ config('services.stripe.key') }}");
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: card,
        billing_details: {
            name: document.getElementById('card-holder-name').value,
        },
    });

    if (error) {
        document.getElementById('card-errors').textContent = error.message;
        return;
    }

    document.getElementById('payment_method').value = paymentMethod.id;
    e.target.submit();
});
</script>
@endsection
