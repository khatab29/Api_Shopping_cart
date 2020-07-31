<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderValidator;

class OrderController extends Controller
{
    /**
    * Store Order With Valied Token.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function store(OrderValidator $request)
    {
        $cart = Cart::where('token', $request->token)->first();
        $products = $cart->products->pluck('id')->toArray();
        $price = $cart->products->pluck('final_price')->toArray();
        $cartTotalPrice = array_sum($price);
        $cartPriceWithTax = round((1 + (10 / 100)) * $cartTotalPrice, 2);

        $validated = $request->validated();

        $order = new Order();
        $order->unique_id = Str::random(10);
        $order->user_id = auth()->user->id ?? null;
        $order->name = $request->name;
        $order->address = $request->address;
        $order->discount_code = $request->discount_code;
        $order->shipment_type = $request->shipment_type;
        $order->total_price = $cartPriceWithTax;
        $order->status = 'processing';
        $order->save();

        $order->products()->attach($products);
        Auth::User() ? Auth::User()->products()->attach($products) : "";

        //$cart->delete();

            return response()->json([
                'status' => 'success',
                'unique_id' => $order->unique_id,
            ]);
    }

    public function destroy(Request $request)
    {
        $order = Order::where('unique_id', $request->unique_id)->first();
        $order->status = 'returned';
        $order->save();
        return response()->json([
            'status' => 'success',
            'data' => 'order has been canceled',
        ]);
    }
}
