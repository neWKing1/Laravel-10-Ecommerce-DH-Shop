@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Create Product Variant</h4>
                        <a href="{{ route('owner.product-variant.index', ['product' => request()->product]) }}"
                            class="btn btn-danger">Back</a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.product-variant.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" />
                            </div>
                            <div class="">
                                <input type="hidden" class="form-control" id="product_id" name="product_id"
                                    value="{{ request()->product }}" />
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="" hidden>Select status</option>
                                    <option value="1">Active</option>
                                    <option value="2">In Atcive</option>
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
