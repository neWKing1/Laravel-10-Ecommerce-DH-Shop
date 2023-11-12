<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Return_;

class CheckoutController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd($request->all());
        $cartTotal = $request['cart_total'];
        return view('customer.pages.checkout', compact('cartTotal'));
    }
    public function pay(Request $request)
    {
        // dd(($request->all()));
        $request['cart_total'] = (float)$request->cart_total;
        // var_dump($request->cart_total);
        // die();
        $request->validate([
            'telephone' => ['required', 'min:9'],
            'name' => ['required'],
            'address' => ['required', 'min:20'],
        ]);
        if ($request['payments'] == 2) {
            return redirect()->route('vnpay', [$request]);
            // $this-
            return $this->checkoutSuccess1($request);
        }
        return $this->checkoutSuccess($request);
    }
    public function vnpayPayment(Request $request)
    {
        // var_dump($request->cart_total);
        // die();
        // dd($request->all());
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('pay-success1');
        $vnp_TmnCode = "CAL4BV81"; //Mã website tại VNPAY
        $vnp_HashSecret = "VJMBKWWRRCWMVNQHRLSLPDSRQSSNTOAV"; //Chuỗi bí mật

        $vnp_TxnRef = uniqid(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = $request->telephone . "~" . $request->address;
        $vnp_OrderType = $request->address;
        $vnp_Amount = (float)$request['cart_total'] * 100 * 24000;
        $vnp_Locale = "VN";
        $vnp_BankCode = $request['bank_code'];
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($request['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
    }
    public function checkoutSuccess($request)
    {
        $this->storeOrder($request);
        $newOrder = Order::orderBy('created_at', 'desc')->first();
        // dd($newOrder);
        Cart::destroy();

        Mail::raw('The order has been placed successfully', function ($message) {
            $message->to(Auth::user()->email)->subject('noreplay');
        });
        return view('customer.pages.pay-success', compact('newOrder'));
    }
    public function checkoutSuccess1(Request $request)
    {
        $this->storeOrder($request);
        $newOrder = Order::orderBy('created_at', 'desc')->first();
        // dd($newOrder);
        Cart::destroy();

        Mail::raw('The order has been placed successfully', function ($message) {
            $message->to(Auth::user()->email)->subject('noreplay');
        });
        return view('customer.pages.pay-success', compact('newOrder'));
    }
    public function storeOrder(Request $request)
    {
        // dd($request->all());
        $order = new Order();
        $order->invoice = $request['vnp_TxnRef'] ? $request['vnp_TxnRef'] :  uniqid();
        $order->user_id = Auth::user()->id;
        $order->order_status = 1;
        $order->total = $request['vnp_Amount'] ? ($request['vnp_Amount'] / 24000 / 100) : $request->cart_total;
        $order->payment_method = $request['vnp_BankCode'] ? $request['vnp_BankCode'] : 'COD';

        if ($request['vnp_OrderInfo']) {
            $separate = explode('~', $request['vnp_OrderInfo']);
            $order->order_address = $separate[1];
            $order->order_phone = $separate[0];
        } else {
            $order->order_address = $request->address;
            $order->order_phone = $request->telephone;
        }
        $order->save();
        // dd($order);

        // // store Order Product
        foreach (Cart::content() as $product) {
            // dd($product->options->variants);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->product_name = $product->name;
            $orderProduct->variants = json_encode($product->options->variants);
            $orderProduct->unit_price = $product->price * $product->qty;
            $orderProduct->qty = $product->qty;

            $findProduct = Product::find($product->id);
            $findProduct->qty = $findProduct->qty - $product->qty;
            $findProduct->save();

            $orderProduct->product_price = $product->price;
            // dd($orderProduct);
            $orderProduct->save();
        }
    }
}
