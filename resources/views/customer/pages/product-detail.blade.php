@extends('customer.layouts.master')
@push('styles')
    <style>
        .carousel-indicators button.thumbnail {
            width: 60px;
        }

        .carousel-indicators img {
            height: 60px;
        }

        .carousel-indicators button.thumbnail:not(.active) {
            opacity: .6;
        }

        .arousel-indicators {
            position: static;
        }
    </style>
@endpush
@section('content')
    <div class="container mx-auto">
        <div class="card">
            <div class="row">
                <div class="col-lg-4 mb-lg-3 mb-5">
                    <div id="carouselExampleIndicators" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                class="active thumbnail border rounded shadow-lg" aria-current="true" aria-label="Slide 1">
                                <img src="{{ asset($product->thumb_image) }}" alt="..." class="d-block">
                            </button>
                            @foreach ($product->productImageGallery as $key => $item)
                                <button type="button" data-bs-target="#carouselExampleIndicators"
                                    data-bs-slide-to="{{ ++$key }}" class="thumbnail border rounded shadow-lg"
                                    aria-label="Slide {{ ++$key }}">
                                    <img src="{{ asset($item->image) }}" class="d-block" alt="..."></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset($product->thumb_image) }}" alt="..." class="w-100">
                            </div>
                            @foreach ($product->productImageGallery as $image)
                                <div class="carousel-item">
                                    <img src="{{ asset($image->image) }}" class="d-block w-100" alt="...">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card-body">
                        <h1 class="card-title">{{ $product->name }}</h1>
                        <h2 class="card-text fw-bold text-danger">
                            {{ number_format($product->price, 0, '.', '.') }}$
                        </h2>
                        @if ($product->offer_price != 0)
                            <del class="text-secondary">{{ number_format($product->offer_price, 0, '.', '.') }}$ </del>
                        @endif
                        <p>
                            {{ $product->short_description }}
                        </p>
                        <div class="row">
                            <div class="col-4">
                                <p>Brand: {{ $product->brand->name }}</p>
                            </div>
                            <div class="col-4">
                                <p>SKU: {{ $product->sku }}</p>
                            </div>
                            <div class="col-4">
                                @if ($product->qty > 0)
                                    <span class="badge text-bg-warning">In stock ({{ $product->qty }} item)</span>
                                @else
                                    <span class="badge text-bg-warning">Stock out</span>
                                @endif
                            </div>
                        </div>
                        {{-- <div>
                            <button class="btn btn-success">Buy Now</button>
                        </div> --}}
                        <form action="" class="shopping-cart-form">
                            <div>
                                <input type="hidden" name="product_id" id="" value="{{ $product->id }}">
                                @foreach ($product->productVariant as $variant)
                                    <div class="mb-3">
                                        <label for="" class="fw-bold">{{ $variant->name }}</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="variants_items[]">
                                            @foreach ($variant->productVariantItem as $variantItem)
                                                <option value="{{ $variantItem->id }}"
                                                    {{ $variantItem->is_default == 1 ? 'selected' : '' }}>
                                                    {{ $variantItem->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-3">
                                <label for="qty" class="form-label fw-bold">Quantity</label>
                                <input class="form-control" type="number" id="qty" min="1" max="100"
                                    value="1" name="qty">
                            </div>
                            <button class="btn btn-danger" type="submit">Add To Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-2 mt-3">
                <p>
                    {!! html_entity_decode($product->long_description) !!}
                </p>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.shopping-cart-form').on('submit', function(e) {
                    e.preventDefault();

                    let formData = $(this).serialize();
                    // console.log(formData);
                    $.ajax({
                        method: 'POST',
                        data: formData,
                        url: "{{ route('add-to-cart') }}",
                        success: function(data) {
                            if (data.status === 'success') {
                                getCartCount()
                                toastr.success(data.message)
                            } else if (data.status == "error") {
                                toastr.error(data.message);
                            }
                        }
                    })
                })

                function getCartCount() {
                    $.ajax({
                        method: 'GET',
                        url: "{{ route('cart-count') }}",
                        success: function(data) {
                            console.log(data);
                            $('#cart-count').text(data)
                        }
                    })
                }
            })
        </script>
    @endpush
