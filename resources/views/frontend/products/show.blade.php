@extends('layouts.app')

@section('content')

    <h3>Purchase Product</h3>

    @if(session('success'))
        <div id="success" class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="payment-form">
        @csrf

        <input type="text" class="form-control mb-3 card_holder_name" placeholder="Card holder name" required>

        <div id="card-element" class="mb-3"></div>
        <div id="card-errors" class="text-danger"></div>

        <button class="btn btn-primary mt-3" id="pay-btn">
            Pay Now
        </button>
    </form>

@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");
        const elements = stripe.elements();
        const card = elements.create('card');
        const payButton =document.getElementById('pay-btn');
        card.mount('#card-element');

        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            payButton.disabled=true;   
            const { paymentIntent, error } = await stripe.confirmCardPayment(
                "{{ $intent->client_secret }}",
                {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: document.querySelector('.card_holder_name').value
                        }
                    }
                }
            );

            if (error) {
                document.getElementById('card-errors').innerText = error.message;
                return;
            }

            //  Payment successful
          window.location.href = "{{ route('products.success') }}";
        });
    </script>
@endsection