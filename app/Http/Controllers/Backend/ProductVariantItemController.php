<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantItemDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;

class ProductVariantItemController extends Controller
{
    //
    public function index(ProductVariantItemDataTable $datatable, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);
        return $datatable->render('owner.product.product-variant-item.index', compact('product', 'variant'));
    }
    public function create(string $productId, string $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $product = Product::findOrFail($productId);
        return view('owner.product.product-variant-item.create', compact('variant', 'product'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'variant_id' => ['integer', 'required'],
            'name' => ['required'],
            'is_default' => ['required'],
            'status' => ['required'],
        ]);

        $variantItem = new ProductVariantItem();
        $variantItem->product_variant_id = $request->variant_id;
        $variantItem->name = $request->name;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();

        toastr('Create Successfully', 'success');

        return redirect()->back();
    }
    public function edit(string $variantItemId)
    {
        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        // dd($variantItem);
        $variant = ProductVariant::findOrFail($variantItem->product_variant_id);
        $product = Product::findOrFail($variant->product_id);
        return view('owner.product.product-variant-item.edit', compact('variantItem', 'variant', 'product'));
    }
    public function update(string $variantItemId, Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'is_default' => ['required'],
            'status' => ['required'],
        ]);

        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        $variantItem->name = $request->name;
        $variantItem->is_default = $request->is_default;
        $variantItem->status = $request->status;
        $variantItem->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }
    public function destroy(string $variantItemId){
        $variantItem = ProductVariantItem::findOrFail($variantItemId);
        $variantItem->delete();
        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $variantItem = ProductVariantItem::findOrFail($request->id);
        // dd($variantItem);
        $variantItem->status = $request->status == 'true' ? 1 : 2;
        // dd($variantItem->status);
        $variantItem->save();

        return response(['message' => 'Status has been updated!']);
    }
}
