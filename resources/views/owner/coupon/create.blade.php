@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Create Coupon</h4>
                        <a href="{{ route('owner.coupons.index') }}" class="btn btn-danger">Back</a>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('owner.coupons.store') }}">
                            @csrf
                         
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" />
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code') }}" />
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}" min="1"/>
                            </div>
                            <div class="mb-3">
                                <label for="max_use" class="form-label">Max Use Per Person</label>
                                <input type="number" class="form-control" id="max_use" name="max_use"
                                    value="{{ old('max_use') }}" min="1"/>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ old('start_date') }}" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ old('end_date') }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select class="form-select" name="discount_type">
                                    <option value="" hidden>Select Type</option>
                                    <option value="Percent">Percentage</option>
                                    <option value="Amount">Amount</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="discount" class="form-label">Discount Value</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    value="{{ old('discount') }}" min="1"/>
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
