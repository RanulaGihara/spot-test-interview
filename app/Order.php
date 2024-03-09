<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
class Order extends Model
{
    protected $fillable = ['customer_name', 'order_value', 'process_id', 'order_id', 'order_status'];


    public function createNewOrder($request)
    {
        $last_order_details = Order::orderBy('id', 'desc')->first();

        if(isset($last_order_details->id) && $last_order_details->id) {
            $next_id = $last_order_details->id + 1;
        }else {
            $next_id = 1;
        }
        $order_id = 'O-'.str_pad(($next_id), 4, '0', STR_PAD_LEFT);


        $order = [
            'order_id' => $order_id,
            'customer_name' => $request['customer_name'],
            'order_value' => $request['order_value'],
            'process_id' => rand(1, 10),
            'order_status' => 'ORDERED'
        ];

        $createdData = Order::create($order);

        return $createdData;

    }

    public function getOrderDetails()
    {
        $orderData = Order::select('id', 'order_id', 'customer_name', 'order_value', 'process_id', 'order_status')
        ->where(function ($query) {
            $query->where('order_status', 'ORDERED')
                ->orWhere('order_status', 'FAILED');
        });
        return $orderData;
    }

    public function submitOrderData($orderData)
    {
        $client = new Client();

        foreach ($orderData as $order) {
            try {
                $response = $client->post('https://wibip.free.beeceptor.com/order', [
                    'json' => [
                        "Order_ID" => $order->order_id,
                        "Customer_Name" => $order->customer_name,
                        "Order_Value" => $order->process_id,
                        "Order_Date" => $order->created_at,
                        "Order_Status" => $order->order_id,
                        "Process_ID" => $order->order_status
                    ]
                ]);

                //update order status
                Order::where('id',  $order->id)->update(['order_status' => 'SUBMITTED']);

                $statusCode = $response->getStatusCode();
                $responseBody = $response->getBody()->getContents();
            } catch (\Exception $e) {
                // revert order status to failed
                Order::where('id',  $order->id)->update(['order_status' => 'FAILED']);
            }

        }
    }
}

