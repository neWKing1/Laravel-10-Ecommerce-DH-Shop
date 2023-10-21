@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center invoice-print">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">Order: #{{ $order->invoice }}</h4>
                        <button class="btn btn-secondary print"><i class="bi bi-printer"></i></button>
                    </div>
                    <div class="card-body">
                        <address>
                            <strong>
                                Billed to: <br>
                                Address: {{ $order->order_address }} <br>
                                Name: {{ $order->user->name }} <br>
                                Phone: {{ $order->order_phone }} <br>
                                Payment Method: {{ $order->payment_method }} <br>
                                Status: {{ $order->order_status == 1 ? 'Compiled' : 'Pending' }} <br>
                                Order Date: {{ date('d-M-Y', strtotime($order->created_at)) }} <br>
                            </strong>
                        </address>
                        <div>
                            <h5>Order Summary:</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Totals</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderProducts as $key => $product)
                                        @php
                                            $variants = json_decode($product->variants);
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ ++$key }}</th>
                                            <td>{{ $product->product_name }}</td>
                                            <td>
                                                @foreach ($variants as $key => $variant)
                                                    <span>{{ $key }}: {{ $variant->name }}</span> <br>
                                                @endforeach
                                            </td>

                                            <td>{{ number_format($product->product_price, 0, '.', '.') }}$</td>
                                            <td>{{ $product->qty }}</td>
                                            <td>{{ number_format($product->unit_price, 0, '.', '.') }}$</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="text-danger fw-bold">Total: {{ number_format($order->total, 0, '.', '.') }}$</p>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.print').on('click', function() {
           let printBody = $('.invoice-print');
           let originalContents = $('body').html();

           $('body').html(printBody);

           window.print();
           
           $('body').html(originalContents);
        })
    })
</script>
@endpush
