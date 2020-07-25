<?php

namespace App\Http\Controllers;

use App\order;
use Illuminate\Http\Request;
use App\Billing\PaymentStrategyInterface;

class PaymentController extends Controller
{
    private $PaymentGateway;

    public function __construct(PaymentStrategyInterface $PaymentGateway)
    {
        $this->PaymentGateway = $PaymentGateway;
    }

    /**
    * Get order based on unique_id.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function getOrder(Request $request)
    {
        $validatedData = $request->validate([
        'unique_id' => 'required'
        ]);
        return Order::where('unique_id', $request->unique_id)->first();
    }

    /**
    * setting and getting discount amount.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function getDiscount(Request $request)
    {
        $order = $this->getOrder($request);
        return $this->PaymentGateway->setDiscount($order->discount_code);
    }

    /**
    *processing Order and charging fot it.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function processOrder(Request $request)
    {

        $order = $this->getOrder($request);
        $this->getDiscount($request);

        $charge = $this->PaymentGateway->charge($order->total_price) ;

        $order->shipment_fees = $charge['shipment_fees'];
        $order->discount_amount = $charge['dicount_amount'];
        $order->final_price = $charge['final_price'];
        $order->confirmation_code = $charge['confirmation_code'];
        $order->status = 'shipping';
        $order->save();

            return $charge;
    }

    /**
    * confirming order payment.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function confirmPayment(Request $request)
    {
        $validatedData = $request->validate([
        'confirmation_code' => 'required'
        ]);

        $order = Order::where('confirmation_code', $request->confirmation_code)->first();

        $order->status = 'delivered';
        $order->save();

        if ($order->save()) {
            return response()->json([
            'massage' => 'payment confirmed'
            ]);
        }
            return response()->json([
            'massage' => 'something went wrong try again'
            ]);
    }
}
