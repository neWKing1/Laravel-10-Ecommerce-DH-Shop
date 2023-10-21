@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Create Product</h4>
                        <a href="{{ route('owner.product.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.product.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="thumb_image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="thumb_image" name="thumb_image"
                                    value="{{ old('thumb_image') }}" />
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" />
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select main-category" name="category_id" id="category_id">
                                            <option value="" hidden>Select category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sub_category_id" class="form-label">Sub Category</label>
                                        <select class="form-select sub-category" name="sub_category_id"
                                            id="sub_category_id">
                                            <option value="" hidden>Select sub category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="child_category_id" class="form-label">Child Category</label>
                                        <select class="form-select child-category" name="child_category_id"
                                            id="child_category_id">
                                            <option value="" hidden>Select child category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select" name="brand_id" id="brand_id">
                                    <option value="" hidden>Select brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="" hidden>Select status</option>
                                    <option value="1">Active</option>
                                    <option value="2">In Atcive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku"
                                    value="{{ old('sku') }}" />
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="{{ old('price') }}" min="0"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="offer_price" class="form-label">Offer Price</label>
                                        <input type="number" class="form-control" id="offer_price" name="offer_price"
                                            value="{{ old('offer_price') }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="qty" class="form-label">Stock Quantity</label>
                                <input type="nummber" class="form-control" id="qty" name="qty"
                                    value="{{ old('qty') }}" min="0" />
                            </div>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" id="short_description" cols="30" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea name="long_description" class="form-control" id="long_description" cols="30"
                                    rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="product_type" class="form-label">Product Type</label>
                                <select class="form-select" name="product_type" id="product_type">
                                    <option value="" hidden>Select product type</option>
                                    <option value="new_arrival">New Arrival</option>
                                    <option value="featured">Featured</option>
                                    <option value="top_product">Top Product</option>
                                    <option value="best_product">Best Product</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('body').on('change', '.main-category', function(e) {
                let id = $(this).val()
                // console.log(id);
                $.ajax({
                    method: "GET",
                    url: "{{ route('owner.product.get-sub-category') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        $('.sub-category').html(
                            '<option value="" hidden>Select sub category</option>')
                        $('.child-category').html(
                            '<option value="" hidden>Select child category</option>')
                        $.each(data, function(i, item) {
                            $('.sub-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    }
                })
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('change', '.sub-category', function(e) {
                let id = $(this).val()
                // console.log(id);
                $.ajax({
                    method: "GET",
                    url: "{{ route('owner.product.get-child-category') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        $('.child-category').html(
                            '<option value="" hidden>Select child category</option>')
                        $.each(data, function(i, item) {
                            $('.child-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    }
                })
            })
        })
    </script>
    <script>
        CKEDITOR.replace('long_description');
    </script>
@endpush
