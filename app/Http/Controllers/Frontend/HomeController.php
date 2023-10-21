<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function home()
    {
        $brands = Brand::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        return view('customer.pages.home', compact('brands', 'products'));
    }
}
