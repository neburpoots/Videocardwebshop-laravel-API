<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            "status" => true,
            "message" => "All products",
            "data" => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "image" => "required",
            "price" => "required"
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->image = $request->image;
        $product->price = $request->price;

        $product->save();

        return response()->json([
            "status" => true,
            "message" => "Product created succesfully",
            "data" => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        if (Product::where([
            "id" => $productId
        ])->exists()) {

            $product = Product::find($productId);

            return response()->json([
                "status" => true,
                "message" => "Product with id $productId found",
                "data" => $product
            ]);
        } else {

            return response()->json([
                "status" => false,
                "message" => "Product doesn't exist"
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_id)
    {

        if (Product::where([
            "id" => $product_id
        ])->exists()) {

            $product = Product::find($product_id);

            //print_r($request->all());die;

            $product->name = isset($request->name) ? $request->name : $product->name;
            $product->image = isset($request->image) ? $request->image : $product->image;
            $product->price = isset($request->price) ? $request->price : $product->price;

            $product->save();

            return response()->json([
                "status" => true,
                "message" => "Product $product->name has been updated"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Product with id $product_id doesn't exist"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        if (Product::where([
            "id" => $product_id
        ])->exists()) {

            $product = Product::find($product_id);

            $product->delete();

            return response()->json([
                "status" => true,
                "message" => "Product has been deleted"
            ]);
        }else{

            return response()->json([
                "status" => false,
                "message" => "Product with id $product_id doesn't exist."
            ], 404);
        }
    }
}

