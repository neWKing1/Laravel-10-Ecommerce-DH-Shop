@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header ">
                        <div class="mb-2">
                            <a href="{{ route('owner.product.index') }}" class="btn btn-danger">Back</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="">Product Variant</h4>
                            <a href="{{ route('owner.product-variant.create', ['product' => request()->product]) }}"
                                class="btn btn-success">
                                <i class="bi bi-plus"></i>
                                Create New</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <h5>Product: {{ $product->name }}</h5>

                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked')
                console.log(isChecked);

                let id = $(this).data('id')
                $.ajax({
                    url: "{{ route('owner.product-variant.change-status') }}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id
                    },
                    success: function(data) {
                        toastr.success(data.message);
                    }
                })
            })
        })
    </script>
@endpush
