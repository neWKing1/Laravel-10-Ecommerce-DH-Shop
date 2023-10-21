<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    //
    public function dashboard()
    {
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $weekOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();
        $yearOrders = Order::whereYear('created_at', Carbon::now()->year)->count();

        $todayEarnings =  Order::whereDate('created_at', Carbon::today())->sum('total');
        $weekEarnings =  Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $monthEarnings =  Order::whereMonth('created_at', Carbon::now()->month)->sum('total');
        $yearEarnings =  Order::whereYear('created_at', Carbon::now()->year)->sum('total');

        // dd($yearOrders);
        return view('owner.dashboard', compact('todayOrders', 'weekOrders', 'monthOrders', 'yearOrders', 'todayEarnings', 'weekEarnings', 'monthEarnings', 'yearEarnings'));
    }
}
