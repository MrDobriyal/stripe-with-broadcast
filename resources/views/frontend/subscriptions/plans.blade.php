@extends('frontend.layouts.app')

@section('title', 'Choose a Plan')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Choose Your Plan</h2>

        <div class="accordion" id="plansAccordion">

            {{-- TRIAL PLAN --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="trialHeading">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#trialPlan">
                        🆓 Trial Plan
                    </button>
                </h2>
                <div id="trialPlan" class="accordion-collapse collapse show" data-bs-parent="#plansAccordion">
                    <div class="accordion-body">
                        <h5>₹0 / 7 Days</h5>
                        <ul>
                            <li>Basic access</li>
                            <li>Limited features</li>
                            <li>No credit card charged</li>
                        </ul>

                        @auth
                            @if(auth()->user()->subscribed())
                                <button class="btn btn-secondary" disabled>
                                    Already Subscribed
                                </button>
                            @else
                                <form method="POST" class="btn btn-outline-primary" action="{{ route('trial.start') }}">
                                    @csrf
                                    Start Trial
                                </form>
                            @endif
                        @else
                            <a href="{{ route('frontend.login') }}" class="btn btn-outline-primary">
                                Login to Continue
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- MEDIUM PLAN --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="mediumHeading">
               
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mediumPlan">
                        ⭐ Medium Plan
                    </button>
                </h2>
                <div id="mediumPlan" class="accordion-collapse collapse" data-bs-parent="#plansAccordion">
                    <div class="accordion-body">
                        <h5>₹200 / Month</h5>
                        <ul>
                            <li>Everything in Trial</li>
                            <li>Priority support</li>
                            <li>Advanced features</li>
                        </ul>

                        @auth
                         @if(auth()->user()->subscribed('default',config('services.stripe.prices.medium')))   
                 <button class="btn btn-secondary" disabled>
                                    Already Subscribed
                                </button>
                @else
                            <a href="{{ route('subscribe.form', 'medium') }}" class="btn btn-primary">
                                Choose Medium
                            </a>
                    @endif
                        @else
                            <a href="{{ route('frontend.login') }}" class="btn btn-primary">
                                Login to Continue
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- PRO PLAN --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="proHeading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#proPlan">
                        🚀 Pro Plan
                    </button>
                </h2>
                <div id="proPlan" class="accordion-collapse collapse" data-bs-parent="#plansAccordion">
                    <div class="accordion-body">
                        <h5>₹500 / Month</h5>
                        <ul>
                            <li>Everything in Medium</li>
                            <li>Unlimited access</li>
                            <li>Dedicated support</li>
                        </ul>

                        @auth
                         @if(auth()->user()->subscribed('default',config('services.stripe.prices.pro')))   
                 <button class="btn btn-secondary" disabled>
                                    Already Subscribed
                                </button>
                @else
                            <a href="{{ route('subscribe.form', 'pro') }}" class="btn btn-success">
                                Go Pro
                            </a>
                    @endif
                        @else
                            <a href="{{ route('frontend.login') }}" class="btn btn-success">
                                Login to Continue
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection