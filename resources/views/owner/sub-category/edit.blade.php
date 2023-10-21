@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Edit Sub Category</h4>
                        <a href="{{ route('owner.sub-category.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.sub-category.update', $subCategory->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Category</label>
                                <select class="form-select" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $subCategory->name }}" />
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="" hidden>Select status</option>
                                    <option value="1" {{ $subCategory->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $subCategory->status == 2 ? 'selected' : '' }}>In Atcive
                                    </option>
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
