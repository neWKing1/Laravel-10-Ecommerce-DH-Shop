<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SubCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SubCategoryDataTable $dataTable)
    {
        //
        return $dataTable->render('owner.sub-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('owner.sub-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:sub_categories,name'],
            'status' => ['required'],
            'category_id' => ['required']
        ]);

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->status = $request->status;
        $subCategory->category_id = $request->category_id;
        $subCategory->save();

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
        $subCategory = SubCategory::findOrFail($id);
        return view('owner.sub-category.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required', 'unique:sub_categories,name,' . $id],
            'status' => ['required'],
            'category_id' => ['required']
        ]);

        $subCategory = SubCategory::findOrFail($id);
        $subCategory->name = $request->name;
        $subCategory->slug = Str::slug($request->name);
        $subCategory->category_id = $request->category_id;
        $subCategory->status = $request->status;
        $subCategory->save();

        toastr('Updated Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $subCategory = SubCategory::findOrFail($id);

        $childCategory = ChildCategory::where('sub_category_id', $subCategory->id)->count();

        if ($childCategory > 0) {
            return response(['status' => 'error', 'message' => 'This item contain sub items for delete this you have delete the sub item first!']);
        }
        $subCategory->delete();

        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $subCategory = SubCategory::findOrFail($request->id);
        $subCategory->status = $request->status == 'true' ? 1 : 2;
        $subCategory->save();

        return response(['message' => 'Status has been updated!']);
    }
}
