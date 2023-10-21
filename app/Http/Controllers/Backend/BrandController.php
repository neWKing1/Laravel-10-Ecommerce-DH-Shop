<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $brandDataTable)
    {
        //
        return $brandDataTable->render('owner.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('owner.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:brands,name'],
            'status' => ['required'],
            'is_featured' => ['required'],
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status;
        $brand->is_featured = $request->is_featured;
        $brand->save();

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
        $brand = Brand::findOrFail($id);
        return view('owner.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:brands,name,' . $id],
            'status' => ['required'],
            'is_featured' => ['required'],
        ]);

        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status;
        $brand->is_featured = $request->is_featured;
        $brand->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $brand = Brand::findOrFail($request->id);
        $brand->status = $request->status == 'true' ? 1 : 2;
        $brand->save();

        return response(['message' => 'Status has been updated!']);
    }
}
