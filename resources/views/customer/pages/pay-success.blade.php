@extends('customer.layouts.master')
@section('content')
    <div class="container mx-auto">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h1>Payment success</h1>
                <a href="{{ route('home') }}" class="btn btn-danger">Back</a>
            </div>
            <div class="card-body">
                <p>Invoice: {{ $newOrder->invoice }}</p>
                <p>Money: {{ number_format($newOrder->total, 0, '.', '.') }}$ </p>
                <p>Payment method: {{ $newOrder->payment_method }}</p>
                <p>Address: {{ $newOrder->order_address }}</p>
                <p>Telephone: {{ $newOrder->order_phone }}</p>
                <p>Date: {{ $newOrder->created_at }}</p>
            </div>
        </div>
    </div>
@endsection
