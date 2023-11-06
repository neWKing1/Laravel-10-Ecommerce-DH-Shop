@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Edit Coupon</h4>
                        <a href="{{ route('owner.coupons.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.coupons.update', $coupon->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $coupon->name }}" />
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ $coupon->code }}" />
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    value="{{ $coupon->quantity }}" min="1" />
                            </div>
                            <div class="mb-3">
                                <label for="max_use" class="form-label">Max Use Per Person</label>
                                <input type="number" class="form-control" id="max_use" name="max_use"
                                    value="{{ $coupon->max_use }}" min="1" />
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ $coupon->start_date }}" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ $coupon->end_date }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select class="form-select" name="discount_type">
                                    <option value="" hidden>Select Type</option>
                                    <option {{ $coupon->discount_type == 'Percent' ? 'selected' : '' }} value="Percent">
                                        Percentage</option>
                                    <option {{ $coupon->discount_type == 'Amount' ? 'selected' : '' }} value="Amount">Amount
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label">Discount Value</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    value="{{ $coupon->discount }}" min="1" />
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="" hidden>Select status</option>
                                    <option {{ $coupon->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $coupon->status == 2 ? 'selected' : '' }}value="2">In Atcive</option>
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
