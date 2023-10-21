@extends('customer.layouts.master')
@section('content')
    <div class="container mx-auto">
        <div class="row">
            <div class="col-lg-2 d-none d-lg-block">
                <div class="card">
                    <div class="card-header">
                        <h4>Brands</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">

                            @foreach ($brands as $brand)
                                <li class="list-group-item">{{ $brand->name }}</li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-10 mx-auto">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="card col-md-6 col-lg-4">
                            <img src="{{ asset($product->thumb_image) }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text fw-bold text-danger">
                                    {{ number_format($product->price, 0, '.', '.') }}$
                                    @if ($product->offer_price != 0)
                                        <del class="text-secondary">{{ number_format($product->offer_price, 0, '.', '.') }}$
                                        </del>
                                    @endif
                                </p>
                                <a href="{{ route('product-detail', $product->slug) }}" class="btn btn-primary">See
                                    details</a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
