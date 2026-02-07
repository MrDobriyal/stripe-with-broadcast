<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPaymentMethod;
class PaymentMethodController extends Controller
{

    public function listCards(Request $request)
    {
        $user = auth()->user();
        $data = UserPaymentMethod::where("user_id", $user->id)->get();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function setDefault(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:user_payment_methods,id',
        ]);

        $user = $request->user();

        $localmethod = UserPaymentMethod::where("id", $request->payment_method_id)->first();

        if ($localmethod && $localmethod->stripe_payment_method_id) {
            $method = $user->paymentMethods()->findOrFail($localmethod->stripe_payment_method_id);

            // Stripe default
            $user->updateDefaultPaymentMethod($method->stripe_payment_method_id);

            // Local default
            $localmethod->update(['is_default' => false]);
            $method->update(['is_default' => true]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Default card updated',
        ]);
    }

    public function createSetupIntent(Request $request)
    {
        $user = $request->user();

        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        $intent = $user->createSetupIntent();

        return response()->json([
            'client_secret' => $intent->client_secret,
        ]);
    }
    public function addCard(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();

        // 1️⃣ Create Stripe customer if needed
        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        // 2️⃣ Attach card via Cashier
        $paymentMethod = $user->addPaymentMethod($request->payment_method);

        // 3️⃣ Make default in Stripe
        $user->updateDefaultPaymentMethod($paymentMethod->id);

        // 4️⃣ Reset local defaults
        $user->paymentMethods()->update(['is_default' => false]);

        // 5️⃣ Save locally (cache only)
        $localMethod = $user->paymentMethods()->create([
            'stripe_payment_method_id' => $paymentMethod->id,
            'pm_type' => $paymentMethod->type,
            'brand' => $paymentMethod->card->brand ?? null,
            'pm_last_four' => $paymentMethod->card->last4 ?? null,
            'is_default' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Card added successfully',
            'data' => [
                'id' => $localMethod->id,
                'brand' => $localMethod->brand,
                'last_four' => $localMethod->pm_last_four,
                'is_default' => true,
            ],
        ]);
    }

    public function deleteCard(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'payment_method_id' => 'required|exists:user_payment_methods,id',
        ]);

        $localMethod = UserPaymentMethod::where('id', $request->payment_method_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $stripeMethod = $user->paymentMethods()
            ->firstWhere('id', $localMethod->stripe_payment_method_id);

        if ($stripeMethod) {

            // If this is the default card
            if ($user->defaultPaymentMethod()?->id === $stripeMethod->id) {

                // Clear default payment method completely
                $user->updateDefaultPaymentMethod(null);
            }

            // Detach from Stripe
            $user->deletePaymentMethod($stripeMethod->id);
        }

        // Delete locally
        $localMethod->delete();

        return response()->json([
            'status' => true,
            'message' => 'Card removed successfully',
        ]);
    }
}
