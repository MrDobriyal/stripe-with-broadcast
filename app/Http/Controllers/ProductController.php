<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Stripe\Stripe;
use Stripe\PaymentIntent;
class ProductController extends Controller
{
    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        // dd($product);
         Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::create([
        'amount' => $product->price * 100, // must be >= ₹50
        'currency' => 'inr',
        'automatic_payment_methods' => [
            'enabled' => true,
            'allow_redirects' => 'never',
        ],
    ]);

        return view('frontend.products.show', compact('product', 'intent'));
    }

    // public function purchase(Request $request, $prodcutId)
    // {
    //     $product = Product::findOrFail($prodcutId);
    //     $user = $request->user();

    //     $paymentMethod = $request->input('payment_method');

    //     try {
    //         $user->createOrGetStripeCustomer();
    //         $user->updateDefaultPaymentMethod($paymentMethod);
    //         $user->charge($product->price * 100, $paymentMethod);
    //     } catch (\Exception $exception) {
    //         return back()->with('error', $exception->getMessage());
    //     }

    //     return back()->with('success', 'Product purchased successfully!');
    // }
}
