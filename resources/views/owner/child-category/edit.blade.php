@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Update Child Category</h4>
                        <a href="{{ route('owner.child-category.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.child-category.update', $childCategory->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select main-category" name="category_id" id="category_id">
                                    <option value="" hidden>Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $childCategory->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sub_category_id" class="form-label">Sub Category</label>
                                <select class="form-select sub-category" name="sub_category_id" id="sub_category_id">
                                    <option value="" hidden>Select sub category</option>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ $subCategory->id == $childCategory->sub_category_id ? 'selected' : '' }}>
                                            {{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $childCategory->name }}" />
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="" hidden>Select status</option>
                                    <option value="1" {{ $childCategory->status == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="2" {{ $childCategory->status == 2 ? 'selected' : '' }}>In Atcive
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.main-category', function(e) {
                let id = $(this).val()
                // console.log(id);
                $.ajax({
                    method: "GET",
                    url: "{{ route('owner.child-category.get-sub-category') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        $.each(data, function(i, item) {
                            $('.sub-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    }
                })
            })
        })
    </script>
@endpush
