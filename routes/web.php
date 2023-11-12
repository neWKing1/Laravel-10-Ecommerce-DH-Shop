<?php

use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'home'])->name('home');

/** Product Detail Route */
Route::get('/product-detail/{slug}', [App\Http\Controllers\Frontend\FrontendProductController::class, 'showProduct'])->name('product-detail');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/** Add to cart route */
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('cart-details', [CartController::class, 'cartDetails'])->name('cart-details');
Route::post('cart/update-quantiy', [CartController::class, 'updateProductQty'])->name('cart.update-quantity');
Route::get('clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');
Route::get('cart/remove-product/{rowId}', [CartController::class, 'removeProduct'])->name('cart.remove-product');
Route::get('cart-count', [CartController::class, 'getCartCount'])->name('cart-count');
Route::get('apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('coupon-calc', [CartController::class, 'couponCalc'])->name('coupon-calc');

// Checkout routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('pay', [CheckoutController::class, 'pay'])->name('pay');
    Route::get('vnpay', [CheckoutController::class, 'vnpayPayment'])->name('vnpay');
    Route::get('pay/success', [CheckoutController::class, 'checkoutSuccess'])->name('pay-success');
    Route::get('pay/success1', [CheckoutController::class, 'checkoutSuccess1'])->name('pay-success1');
    /** Order routes */
    Route::get('orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/show/{id}', [UserOrderController::class, 'show'])->name('orders.show');

});



Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('github.login');
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    // $user->token
    $user = User::firstOrCreate([
        'email' => $user->email
    ], [
        'name' => $user->name,
        'password' => bcrypt(Str::random(24))
    ]);

    Auth::login($user, true);

    return redirect()->route('home');
});
