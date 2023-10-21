@extends('customer.layouts.master')
@section('content')
    <div class="container mx-auto">
        <form action="{{ route('pay') }}" method="POST">
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
                                    aria-describedby="emailHelp" value="{{ Auth::user()->name }}" required name="name">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail2" class="form-label">Telephone Number</label>
                                <input type="text" class="form-control" id="exampleInputEmail2"
                                    aria-describedby="emailHelp" required name="telephone">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail3" class="form-label">Address</label>
                                <input type="text" class="form-control" id="exampleInputEmail3"
                                    aria-describedby="emailHelp" required name="address">
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
                            <p class="text-danger fw-bold">{{ number_format($total, '0', '.', '.') }}$</p>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="total" value="{{ $total }}">
                            <button type="submit" class="btn btn-primary w-100" name="redirect">Pay</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection

