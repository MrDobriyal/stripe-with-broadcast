<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\SubscriptionController;
use Laravel\Cashier\Http\Controllers\WebhookController;
// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', fn() => view('frontend.home'))->name('frontend.home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('frontend.login');
Route::post('/login', [AuthController::class, 'login'])->name('frontend.login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('frontend.register');
Route::post('/register', [AuthController::class, 'register'])->name('frontend.register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('frontend.logout');

//products
Route::post('product/{id}/purchase', [ProductController::class, 'purchase'])->name('products.purchase');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('frontend.product.show');
Route::get('/payment-success', function () {
    return redirect()->back()->with('success', 'Payment successful!');
})->name('products.success');

//subscriptions
Route::get("/plans", [SubscriptionController::class, "show"])->name('subscriptions.plans');
// Route::post("/subscriptions", [SubscriptionController::class, "subscribe"])->name('subscribe');
// Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');
Route::post('/trial/start', [SubscriptionController::class, 'startTrial'])->name('trial.start');
// Route::get('/plans', [SubscriptionController::class, 'plans'])
//     ->name('plans');

Route::get('/subscribe/{plan}', [SubscriptionController::class, 'subscribeForm'])
    
    ->name('subscribe.form');

Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])
    ->name('subscribe.store');

Route::post(
    '/stripe/webhook',
    action: [WebhookController::class, 'handleWebhook']
);

Route::post('/send-message', action: [ChatController::class, 'send'])->name('frontend.message.send');
Route::get('/chat/{recieverId}',[ChatController::class,'chat'])->name('frontend.chat.index');