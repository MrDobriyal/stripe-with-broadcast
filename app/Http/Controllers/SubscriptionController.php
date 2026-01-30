<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function show()
    {
        return view('frontend.subscriptions.plans');
    }

     public function store(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:medium,pro',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();

        if ($user->subscribed('default')) {
            return back()->with('error', 'You are already subscribed.');
        }

        $priceId = config("services.stripe.prices.{$request->plan}");

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);

        $user->newSubscription('default', $priceId)
             ->create($request->payment_method);

        return redirect()->route('dashboard')
            ->with('success', 'Subscription activated successfully!');
    }
     public function subscribeForm(string $plan)
    {
        if (!in_array($plan, ['medium', 'pro'])) {
            abort(404);
        }

        return view('frontend.subscriptions.subscribe', compact('plan'));
    }
     public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:medium,pro',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();

        if ($user->subscribed('default')) {
            return back()->with('error', 'You already have an active subscription.');
        }

        $priceId = config("services.stripe.prices.{$request->plan}");

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($request->payment_method);

            $user->newSubscription('default', $priceId)
                 ->create($request->payment_method);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('subscriptions.plans')
            ->with('success', 'Subscription activated successfully!');
    }

    public function startTrial(Request $request)
{
    $user = $request->user();

    if ($user->subscribed('default')) {
        return back()->with('error', 'You already have a subscription.');
    }

    if ($user->trial_ends_at && now()->lt($user->trial_ends_at)) {
        return back()->with('error', 'Trial already active.');
    }

    $user->update([
        'trial_ends_at' => now()->addDays(7),
    ]);

    return back()->with('success', 'Trial started for 7 days!');
}
}
