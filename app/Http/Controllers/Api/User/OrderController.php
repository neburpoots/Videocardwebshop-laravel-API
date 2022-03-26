<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        $name = auth()->user()->name;

        $orders = Order::with('products')->where('user_id', $id)->get();

        return response()->json([
            "status" => true,
            "message" => "All orders for $name",
            "data" => $orders
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
            "products" => "required",
        ]);

        $id = auth()->user()->id;

        $order = new Order();
        $order->user_id = $id;
        $order->order_date = Carbon::now()->format('Y-m-d H:i:s');

        if($order->save()) {
            $products = $request->products;

            if($this->checkOrderLine($products)) {
                $order->products()->sync($products);
            } else {
                $order->delete();
                $order->products()->detach();

                return response()->json([
                    "status" => false,
                    "message" => "Unprocessable entity"
                ], 422);
            }
        }
       

        return response()->json([
            "status" => true,
            "message" => "Order created succesfully",
            "data" => $order
        ], 201);
    }

    public function checkOrderLine(Array $products) : bool 
    {

        foreach($products as $product) {
            if(!isset($product["product_id"]) || !isset($product['quantity'])) {
                return false;
            }
            if (!Product::where(["id" => $product['product_id']])->exists()) {
                return false;
            }
        }
        return true;
    }
}
