@extends('customer.layouts.master')
@section('content')
    <div class="container mx-auto">
        <form action="{{ route('pay') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-header">
                            <h1>Information</h1>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="exampleInputEmail1"
                                    value="{{ Auth::user()->name }}" required name="name">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail2" class="form-label">Telephone Number</label>
                                <input type="text" class="form-control" id="exampleInputEmail2" required
                                    name="telephone">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail3" class="form-label">Address</label>
                                <input type="text" class="form-control" id="exampleInputEmail3" required name="address">

                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail3" class="form-label">Payments</label>
                                <select class="form-select" aria-label="Default select example" id="payments"
                                    name="payments">
                                    <option value="1">COD</option>
                                    <option value="2">VNPay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h5>Total</h5>
                            <p class="text-danger fw-bold">{{ number_format($cartTotal, 0, '.', '.') }}$</p>
                            <input type="hidden" name="cart_total" value="{{ $cartTotal }}">
                        </div>
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100" name="redirect" value="">Pay</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection
