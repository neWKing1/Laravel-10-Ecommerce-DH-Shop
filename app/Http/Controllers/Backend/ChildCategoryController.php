<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ChildCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChildCategoryDataTable $dataTable)
    {
        //
        return $dataTable->render('owner.child-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('owner.child-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:child_categories,name'],
            'status' => ['required'],
            'category_id' => ['required'],
            'sub_category_id' => ['required']
        ]);

        $childCategory = new ChildCategory();
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name);
        $childCategory->status = $request->status;
        $childCategory->category_id = $request->category_id;
        $childCategory->sub_category_id = $request->sub_category_id;
        $childCategory->save();

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
        $categories = Category::all();
        $childCategory = ChildCategory::findOrFail($id);
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)
        ->where('status', 1)
        ->get();
        return view('owner.child-category.edit', compact('childCategory', 'subCategories', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:categories,name,' . $id],
            'status' => ['required'],
            'category_id' => ['required'],
            'sub_category_id' => ['required']

        ]);
    
        $childCategory = ChildCategory::findOrFail($id);
        $childCategory->name = $request->name;
        $childCategory->slug = Str::slug($request->name);
        $childCategory->category_id = $request->category_id;
        $childCategory->sub_category_id = $request->sub_category_id;
        $childCategory->status = $request->status;
        $childCategory->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $childCategory = ChildCategory::findOrFail($id);
        $childCategory->delete();

        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $childCategory = ChildCategory::findOrFail($request->id);
        $childCategory->status = $request->status == 'true' ? 1 : 2;
        $childCategory->save();

        return response(['message' => 'Status has been updated!']);
    }
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)
            ->where('status', 1)
            ->get();
        // return dd($request->all());
        return $subCategories;
    }
}
