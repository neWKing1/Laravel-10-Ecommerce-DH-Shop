@extends('customer.layouts.master')
@push('styles')
@endpush
@section('content')
    <div class="container mx-auto">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h1>Card Details</h1>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Details</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">
                                        <button class="btn btn-primary clear-cart">
                                            Clear
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset($item->options->image) }}" alt="" class="img-fluid"
                                                width="100px">
                                        </td>
                                        <td>
                                            <p>{{ $item->name }}</p>
                                            @foreach ($item->options->variants as $key => $variant)
                                                <span>{{ $key }}: {{ $variant['name'] }}</span> <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="mb-3 d-flex">
                                                <button class="btn btn-warning product-decrement">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="form-control mx-1  product-qty"
                                                    value="{{ $item->qty }}" style="width: 60px;" readonly
                                                    data-rowid="{{ $item->rowId }}">
                                                <button class="btn btn-success product-increment">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 0, '.', '.') }}$</td>
                                        <td id="{{ $item->rowId }}">
                                            {{ number_format($item->price * $item->qty, 0, '.', '.') }}$</td>
                                        <td>
                                            <a href="{{ route('cart.remove-product', $item->rowId) }}"
                                                class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($cartItems) == 0)
                                    <tr>
                                        <td class="border-bottom-0">Cart is empty!</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Total card</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout') }}" method="GET" class="mb-3">
                            @csrf
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold">Subtotal:</p>
                                <p id="total">{{ number_format($total, 0, '.', '.') }}$</p>
                                <input type="hidden" value="{{ $total }}" name="total">
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold">Discount:</p>
                                <p id="discount">0$</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="fw-bold">Total:</p>
                                <p id="cart_total">{{ number_format($total, 0, '.', '.') }}$</p>
                                <input type="hidden" value="{{ $total }}" name="cart_total" id="input_cart_total">
                            </div>

                            @if (count($cartItems) != 0)
                                <button class="btn btn-secondary" type="submit">Checkout</button>
                            @endif
                        </form>
                        <form id="coupon_form" class="d-flex mb-3">
                            <input class="form-control me-2" type="text" placeholder="Coupon code" name="coupon_code">
                            <button class="btn btn-outline-success" type="submit">Apply</button>
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
            $('.product-increment').on('click', function() {
                let input = $(this).siblings('.product-qty')
                let quantity = parseInt(input.val()) + 1;
                console.log(quantity);
                let rowId = input.data('rowid')
                $.ajax({
                    url: "{{ route('cart.update-quantity') }}",
                    method: "POST",
                    data: {
                        quantity: quantity,
                        rowId: rowId
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            input.val(quantity)
                            let productId = "#" + rowId;
                            toastr.success(data.message)
                            $(productId).text(data.product_total + "$")
                            $('#total').text(data.total + "$");
                            calcCouponDiscount()
                        } else if (data.status === 'error') {
                            toastr.error(data.message)
                        }
                    }
                })
            })
            $('.product-decrement').on('click', function() {
                let input = $(this).siblings('.product-qty')
                let quantity = parseInt(input.val()) - 1;
                if (quantity < 1) {
                    quantity = 1
                }
                input.val(quantity)
                // console.log(quantity);
                let rowId = input.data('rowid')
                $.ajax({
                    url: "{{ route('cart.update-quantity') }}",
                    method: "POST",
                    data: {
                        quantity: quantity,
                        rowId: rowId
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            let productId = "#" + rowId;
                            toastr.success(data.message)
                            $(productId).text(data.product_total + "$")
                            $('#total').text(data.total + "$");
                            calcCouponDiscount()
                        }
                    }
                })
            })
            $('.clear-cart').on('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will clear your cart!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: 'GET',
                            url: "{{ route('clear-cart') }}",
                            success: function(data) {
                                console.log(data);
                                if (data.status == 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        data.message,
                                        'success'
                                    )
                                    window.location.reload();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        })
                    }
                })
            })

            // apply coupon
            $('#coupon_form').on('submit', function(e) {
                e.preventDefault();
                let fromData = $(this).serialize();
                console.log(fromData);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('apply-coupon') }}",
                    data: fromData,
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'error') {
                            toastr.error(data.message)
                        } else if (data.status == 'success') {
                            calcCouponDiscount()
                            toastr.success(data.message)
                        }
                    }
                })
            })

            function calcCouponDiscount() {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('coupon-calc') }}",
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'error') {
                            toastr.error(data.message)
                        } else if (data.status == 'success') {
                            $('#discount').text(data.discount + '$');
                            $('#cart_total').text(data.cart_total + '$');
                            $('#input_cart_total').val(data.cart_total);
                        }
                    }
                })
            }
        })
    </script>
@endpush
