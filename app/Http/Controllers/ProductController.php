<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\product\ProductResource;
use App\Http\Resources\product\ProductCollection;
use App\Http\Requests\ProductValidator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return ProductCollection::collection(Product::paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductValidator $request)
    {
        $validated = $request->validated();
        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'discount' => $request->discount,
            'final_price' => round((1 - ($request->discount / 100)) * $request->price, 2)
        ]);

            return response()->json([
                'data' => new ProductResource($product)
            ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductValidator $request, Product $product)
    {
        $validated = $request->validated();

        $product->title = $request->title;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->final_price = round((1 - ($request->discount / 100)) * $request->price, 2);
        $product->save();

            return response()->json([
                'data' => new ProductResource($product)
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete;
            return response()->json([
                'message' => 'Product deleted'
            ], 200);
    }
}
