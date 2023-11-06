@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">All Banner</h4>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('owner.banner.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Upload Image <code>(Multiple image
                                        support)</code> </label>
                                <input type="file" class="form-control" multiple name="image[]">
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
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked')
                console.log(isChecked);

                let id = $(this).data('id')
                $.ajax({
                    url: "{{ route('owner.banner.change-status') }}",
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
