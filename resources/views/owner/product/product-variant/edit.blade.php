@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Edit Product Variant</h4>
                        <a href="{{ route('owner.product-variant.index', ['product' => $variant->product_id]) }}"
                            class="btn btn-danger">Back</a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.product-variant.update', $variant->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $variant->name}}" />
                            </div>
                            <div class="">
                                <input type="hidden" class="form-control" id="product_id" name="product_id"
                                    value="{{ $variant->product_id }}" />
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option {{ $variant->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $variant->status == 2 ? 'selected' : '' }} value="2">In Atcive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
