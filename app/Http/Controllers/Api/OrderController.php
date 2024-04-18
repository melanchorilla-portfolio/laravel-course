<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Order;
use App\Helper\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $orders = Order::query();

        $orders->when($userId, function($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });

        return ApiHelper::sendResponse(data: $orders, message: "Get All Orders");
    }


    public function store(Request $request)
    {
        $user = $request->input('user');
        $course = $request->input('course');

        $order = Order::create([
            'user_id' => $user['id'],
            'course_id' => $course['id']
        ]);

        $transactionDetails = [
            'order_id' => $order->id.'-'.Str::random(5),
            'gross_amount' => $course['price']
        ];

        $itemDetails = [
            [
                'id' => $course['id'],
                'price' => $course['price'],
                'quantity' => 1,
                'name' => $course['name'],
                'brand' => 'OnlineLearningWithDicky',
                'category' => 'Online Course'
            ]
        ];

        $customerDetails = [
            'first_name' => $user['name'],
            'email' => $user['email']
        ];

        $midtransParams = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails
        ];

        $midtransSnapUrl = $this->getMidtransSnapUrl($midtransParams);
        $order->snap_url = $midtransSnapUrl;

        $order->metadata = [
            'course_id' => $course['id'],
            'course_price' => $course['price'],
            'course_name' => $course['name'],
            'course_thumbnail' => $course['thumbnail'],
            'course_level' => $course['level']
        ];


        try {
            $orderCreated = $order->save();

            return ApiHelper::sendResponse(201, message: "Data added succesfully", data: $orderCreated);
        } catch (Exception $e) {
            return ApiHelper::sendResponse(500, "error", $e->getMessage());
        }
    }


    private function getMidtransSnapUrl($params)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_PRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_3DS');

        $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return $snapUrl;
    }
}
