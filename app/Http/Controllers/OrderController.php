<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function createNewOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'order_value' => 'required|numeric',
        ]);

        $request = $request->all();
        $order = new Order();
        $createdOrder = $order->createNewOrder($request);

        return response()->json([
            'order_id' => $createdOrder->id,
            'process_id' => $createdOrder->process_id,
            'order_status' => $createdOrder->order_status,
            'message' => 'Order created successfully',
        ], 201);
    }
}

