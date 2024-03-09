<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Jobs\SubmitOrderDetails;

class SubmitOrderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:submit_order_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit order data every minute to third party api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = new Order();
        $orderData = $order->getOrderDetails();
        
        $orderData->chunk(5, function($orderData) {
            $orderIdsArray = $orderData->pluck('id');
            Order::whereIn('id', $orderIdsArray)
            ->update(['order_status' => 'PROCESSING']);
            $submitOrderJob = dispatch(new SubmitOrderDetails($orderData));
        });
        return true;
    }
}
