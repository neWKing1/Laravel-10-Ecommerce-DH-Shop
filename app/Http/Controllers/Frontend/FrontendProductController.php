<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendProductController extends Controller
{
    //
    public function showProduct(string $slug)
    {
        // dd($slug);
        $product = Product::where('status', 1)->where('slug', $slug)
            ->with(['productVariant' => function ($query) {
                $query->where('status', 1)
                ->with(['productVariantItem' => function($query){
                    $query->where('status', 1);
                }]);
            }])
            ->first();
        // dd($slug);
        return view('customer.pages.product-detail', compact('product'));
    }
}
