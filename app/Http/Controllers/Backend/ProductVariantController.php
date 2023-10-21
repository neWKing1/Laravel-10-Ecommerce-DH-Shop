<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductVariantDataTable $dataTable)
    {
        //
        $product = Product::findOrFail($request->product);
        return $dataTable->render('owner.product.product-variant.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('owner.product.product-variant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_id' => ['required'],
            'name' => ['required'],
            'status' => ['required']
        ]);

        $variant = new ProductVariant();
        $variant->product_id = $request->product_id;
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();

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
        $variant = ProductVariant::findOrFail($id);
        return view('owner.product.product-variant.edit', compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required'],
            'status' => ['required']
        ]);

        $variant = ProductVariant::findOrFail($id);
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $variant = ProductVariant::findOrFail($id);
        $variantItem = ProductVariantItem::where('product_variant_id', $variant->id)->count();

        if ($variantItem > 0) {
            return response(['status' => 'error', 'message' => 'This item contain sub items for delete this you have delete the sub item first!']);
        } else {
            $variant->delete();
        }


        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $variant = ProductVariant::findOrFail($request->id);
        // dd($variant);
        $variant->status = $request->status == 'true' ? 1 : 2;
        // dd($variant->status);
        $variant->save();

        return response(['message' => 'Status has been updated!']);
    }
}
