<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CartValidator;

class CartController extends Controller
{
    /**
    *
    *
    *Genrate unique cart token.
    *
    */
    public function genrateToken()
    {
        $token = Str::random(60);
        hash('sha256', $token);
             return $token;
    }

    /**
    * Get cart with valied token.
    *
    * @param \Illuminate\Http\Request $request
    *
    */
    public function getCart(Request $request)
    {
        return Cart::where('token', $request->token)->first();
    }

    /**
    * Create new cart.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function createCart($request)
    {
        $cart = new Cart();
        $cart->forceFill([
            'token' => $request->token
        ])->save();
        $cart->products()->attach($request->product_id);
        return response()->json([
            'status' => 'Success',
            'data' => 'product added to cart successfully'
        ], 200);
    }

    /**
    * Add products to valied cart or create new cart.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function addToCart(CartValidator $request)
    {
        $validated = $request->validated();

        $cart = $this->getCart($request);
        if (!$cart) {
            return $this->createCart($request);
        }
        $cart->products()->attach($request->product_id);
        return response()->json([
            'status' => 'Success',
            'data' => 'cart updated successfully'
        ], 200);
    }

    /**
    * Checkout the cart with selected products.
    *
    * @param \Illuminate\Http\Request $request
    *
    *
    */
    public function checkout(CartValidator $request)
    {
        $validated = $request->validated();

        $cart = $this->getCart($request);
        $cartItems = $cart->products->count();
        $products = $cart->products->pluck('title')->toArray();
        $price = $cart->products->pluck('final_price')->toArray();
        $productsWithPrice = array_merge($products, $price);
        $totalPrice = array_sum($price);
        $priceWithTax = round((1 + (10 / 100)) * $totalPrice, 2);

        return response()->json([
            'status' => 'success',
            'custmer email' => auth()->user()->email ?? "",
            'cart items' => $cartItems,
            'products Pricing' => $productsWithPrice,
            'total price' => $totalPrice,
            'price with tax' => $priceWithTax
        ], 200);
    }
}
