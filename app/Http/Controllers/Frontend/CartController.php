<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //
    public function addToCart(Request $request)
    {
        // dd($request->all());
        $product = Product::findOrFail($request->product_id);

        if ($product->qty == 0) {
            return response(['status' => 'error', 'message' => 'Product stock out']);
        } else if ($product->qty < $request->qty) {
            return response(['status' => 'error', 'message' => 'Quantity not available in out stock']);
        }

        $variants = [];

        if ($request->has('variants_items')) {
            foreach ($request->variants_items as $item_id) {
                $variantItem = ProductVariantItem::find($item_id);
                // $variants[] = $variantItem;
                $variants[$variantItem->productVariant->name]['name'] = $variantItem->name;
            }
            // dd($variants);
        }

        $cartData = [];
        $cartData['id'] = $product->id;
        $cartData['name'] = $product->name;
        $cartData['qty'] = $request->qty;
        $cartData['weight'] = 10;
        $cartData['price'] = $product->price;
        $cartData['options']['variants'] = $variants;
        $cartData['options']['image'] = $product->thumb_image;
        // dd($cartData);
        Cart::add($cartData);

        return response(['status' => 'success', 'message' => 'Added to cart successfully']);
    }

    public function cartDetails(Request $request)
    {
        $cartItems = Cart::content();
        // dd($cartItems);
        $total = $this->getCartTotal();
        return view('customer.pages.cart-detail', compact('cartItems', 'total'));
    }
    public function updateProductQty(Request $request)
    {
        // dd($request->all());
        $productId = Cart::get($request->rowId)->id;
        $product = Product::findOrFail($productId);
        if ($product->qty < $request->quantity) {
            return response(['status' => 'error', 'message' => 'Quantity not available in out stock']);
        }
        Cart::update($request->rowId, $request->quantity);
        $productTotal = $this->getProductTotal($request->rowId);
        $total = $this->getCartTotal();
        return response(
            [
                'status' => 'success',
                'message' => 'Product Quantity Updated',
                'product_total' => $productTotal,
                'total' => $total
            ]
        );
    }
    public function getProductTotal($rowId)
    {
        $product = Cart::get($rowId);
        $total = $product->price * $product->qty;
        return $total;
    }
    public function clearCart()
    {
        Cart::destroy();
        return response(['status' => 'success', 'message' => 'Cart cleared successfully']);
    }
    public function removeProduct($rowId)
    {
        Cart::remove($rowId);
        return redirect()->back();
    }
    public function getCartCount()
    {
        return Cart::content()->count();
    }
    function getCartTotal()
    {
        $total = 0;
        foreach (Cart::content() as $product) {
            $total += $product->price * $product->qty;
        }
        return $total;
    }
    public function applyCoupon(Request $request)
    {
        // dd($request->all());
        if ($request->coupon_code == null) {
            return response([
                'status' => 'error',
                'message' => "Coupon filed is required"
            ]);
        }

        $coupon = Coupon::where(['code' => $request->coupon_code, 'status' => 1])->first();

        if ($coupon == null) {
            return response([
                'status' => 'error',
                'message' => "Coupon not exist"
            ]);
        } else if ($coupon->start_date > Date('Y-m-d')) {
            return response([
                'status' => 'error',
                'message' => "Coupon not exist"
            ]);
        } else if ($coupon->end_date < Date('Y-m-d')) {
            return response([
                'status' => 'error',
                'message' => "Coupon is expired"
            ]);
        } else if ($coupon->total_used >= $coupon->quantity) {
            return response([
                'status' => 'error',
                'message' => "You can not apply this coupon"
            ]);
        }

        if ($coupon->discount_type == 'Amount') {
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'amount',
                'discount' => $coupon->discount
            ]);
        } else if ($coupon->discount_type == 'Percent') {
            Session::put('coupon', [
                'coupon_name' => $coupon->name,
                'coupon_code' => $coupon->code,
                'discount_type' => 'percent',
                'discount' => $coupon->discount
            ]);
        };

        return response([
            'status' => 'success',
            'message' => "Coupon applied"
        ]);
    }
    public function couponCalc()
    {
        if (Session::has('coupon')) {
            $coupon = Session::get('coupon');

            if ($coupon['discount_type'] == 'amount') {
                $total = (double)$this->getCartTotal()  - (double)$coupon['discount'];
                return response([
                    'status' => 'success',
                    'cart_total' => (double)$total,
                    'discount' => $coupon['discount']
                ]);
            } else if ($coupon['discount_type'] == 'percent') {
                $discount = (double)$this->getCartTotal() * (double)$coupon['discount'] / 100;
                $total = (double)$this->getCartTotal() - (double)$discount;
                return response([
                    'status' => 'success',
                    'cart_total' => (double)$total,
                    'discount' => $discount
                ]);
            }
        } else {
            return 0;
        }
    }
}
