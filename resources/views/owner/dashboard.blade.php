@extends('owner.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                Today Orders: {{ $todayOrders }}
                            </div>
                            <div class="col-3">
                                Week Orders: {{ $weekOrders }}
                            </div>
                            <div class="col-3">
                                Month Orders: {{ $monthOrders }}
                            </div>
                            <div class="col-3">
                                Year Orders: {{ $yearOrders }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                Today Earnings: {{ number_format($todayEarnings, 0, '.', '.') }}$
                            </div>
                            <div class="col-3">
                                Week Earnings: {{ number_format($weekEarnings, 0, '.', '.') }}$
                            </div>
                            <div class="col-3">
                                Month Earnings: {{ number_format($monthEarnings, 0, '.', '.') }}$
                            </div>
                            <div class="col-3">
                                Year Earnings: {{ number_format($yearEarnings, 0, '.', '.') }}$
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
