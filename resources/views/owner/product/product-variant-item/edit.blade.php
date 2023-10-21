@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Edit Product Variant Item</h4>
                        <a href="{{ route('owner.product-variant-item.index', ['productId' => $product->id, 'variantId' => $variant->id]) }}"
                            class="btn btn-danger">Back</a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.product-variant-item.update', $variantItem->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="variant_name" class="form-label">Variant Name</label>
                                <input type="text" class="form-control" id="variant_name" name="variant_name"
                                    value="{{ $variant->name }}" readonly disabled />
                            </div>
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="variant_id" name="variant_id"
                                    value="{{ $variantItem->id }}" readonly />
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $variantItem->name }}" />
                            </div>
                            <div class="mb-3">
                                <label for="is_default" class="form-label">Is default</label>
                                <select class="form-select" aria-label="Default select example" id="is_default"
                                    name="is_default">
                                    <option {{ $variantItem->is_default == 1 ? 'selected' : '' }} value="1">Yes
                                    </option>
                                    <option {{ $variantItem->is_default == 2 ? 'selected' : '' }} value="2">No</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option {{ $variantItem->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $variantItem->status == 2 ? 'selected' : '' }} value="2">In Atcive</option>
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
