<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    //
    public function index(UserOrderDataTable $datatable){
        return $datatable->render('customer.order.index');
    }
    public function show(string $id){
        $order = Order::findOrFail($id);
        return view('customer.order.show', compact('order'));
    }
}
