<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;

class BannerController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BannerDataTable $bannerDataTable)
    {
        //
        return $bannerDataTable->render('owner.banner.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'image.*' => ['required', 'image'],
        ]);

        // hanlde image upload
        $imagePaths = $this->uploadMultiImage($request, 'image', 'uploads');

        foreach($imagePaths as $path){
            $banner = new Banner();
            $banner->image = $path;
            $banner->status = 1;
            $banner->save();
        }

        toastr('Upload Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $banner = Banner::findOrFail($id);
        $this->deleteImage($banner->image);
        $banner->delete();
        
        return response(['status' => 'success']);
    }
    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $banner = Banner::findOrFail($request->id);
        $banner->status = $request->status == 'true' ? 1 : 2;
        // dd($banner->status);
        $banner->save();

        return response(['message' => 'Status has been updated!']);
    }
}
