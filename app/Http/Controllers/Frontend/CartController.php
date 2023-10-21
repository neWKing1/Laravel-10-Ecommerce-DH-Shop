<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

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
}
