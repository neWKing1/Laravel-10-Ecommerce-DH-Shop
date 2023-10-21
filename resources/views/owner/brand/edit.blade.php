@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Edit Brand</h4>
                        <a href="{{ route('owner.brand.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.brand.update', $brand->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $brand->name }}" />
                            </div>
                            <div class="mb-3">
                                <label for="is_featured" class="form-label">Is Featured</label>
                                <select class="form-select" name="is_featured" id="is_featured">
                                    <option value="" hidden>Select</option>
                                    <option value="1" {{ $brand->is_featured == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $brand->is_featured == 2 ? 'selected' : '' }}>In Atcive
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="" hidden>Select status</option>
                                    <option value="1" {{ $brand->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $brand->status == 2 ? 'selected' : '' }}>In Atcive</option>
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
