<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\ProductImageGallery;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $datatable)
    {
        //
        return $datatable->render('owner.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        $brands = Brand::all();
        return view('owner.product.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'thumb_image' => ['required', 'image'],
            'name' => ['required'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
            'sku' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            'short_description' => ['required'],
            'long_description' => ['required'],
            'status' => ['required'],
        ]);
        /**handle the image upload */
        $imagePath = $this->uploadImage($request, 'thumb_image', 'uploads');

        $product = new Product();
        $product->thumb_image = $imagePath;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->qty = $request->qty;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->status = $request->status;
        $product->product_type = $request->product_type;
        $product->save();

        toastr('Create Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->get();
        $brands = Brand::all();
        return view('owner.product.edit', compact('product', 'categories', 'brands', 'subCategories', 'childCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'thumb_image' => ['nullable', 'image'],
            'name' => ['required'],
            'category_id' => ['required'],
            'brand_id' => ['required'],
            'sku' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            'short_description' => ['required'],
            'long_description' => ['required'],
            'status' => ['required'],
        ]);
        $product = Product::findOrFail($id);

        /**handle the image update  */
        $imagePath = $this->updateImage($request, 'thumb_image', 'uploads', $product->thumb_image);

        $product->thumb_image = empty($imagePath) ? $product->thumb_image : $imagePath;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->qty = $request->qty;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->status = $request->status;
        $product->product_type = $request->product_type;
        $product->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        // dd($id);
        $product = Product::findOrFail($id);
        /** delete the main product image */
        $this->deleteImage($product->thumb_image);
        // /** delete product gallery image */
        $galleryImages = ProductImageGallery::where('product_id', $product->id)->get();
        // dd($galleryImages)
        foreach($galleryImages as $image){
            $this->deleteImage($image->image);
            $image->delete();
        }
        /** delete product variant if exist */
        $variants = ProductVariant::where('product_id', $product->id)->get();
        foreach($variants as $variant){
            $variant->productVariantItem()->delete();
            $variant->delete();
        }
        $product->delete();
        return response(['status' => 'success']);


    }

    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)->get();
        return $subCategories;
    }
    public function getChildCategories(Request $request)
    {
        $childCategries = ChildCategory::where('sub_category_id', $request->id)->get();
        return $childCategries;
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $product = Product::findOrFail($request->id);
        $product->status = $request->status == 'true' ? 1 : 2;
        // dd($product->status);
        $product->save();

        return response(['message' => 'Status has been updated!']);
    }
}
