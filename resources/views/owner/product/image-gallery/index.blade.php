@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Product Image Gallery</h4>
                        <a href="{{ route('owner.product.index') }}" class="btn btn-danger">Back</a>
                    </div>

                    <div class="card-body">
                        <h5>Product name: {{ $product->name }}</h5>
                        <form enctype="multipart/form-data" action="{{ route('owner.product-image-gallery.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Upload Image <code>(Multiple image support)</code> </label>
                                <input type="file" class="form-control" multiple name="image[]">
                                <input type="hidden" value="{{ $product->id }}" name="product_id">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
