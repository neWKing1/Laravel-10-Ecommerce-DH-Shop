<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function home()
    {
        $brands = Brand::where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $banners = Banner::where('status', 1)->get();
        // dd($banners);
        return view('customer.pages.home', compact('brands', 'products', 'banners'));
    }
}
