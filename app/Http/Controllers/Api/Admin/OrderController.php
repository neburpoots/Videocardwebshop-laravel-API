<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('products', 'user')->paginate(10);

        return response()->json([
            "status" => true,
            "message" => "All orders",
            "data" => $orders
        ]);
    }

            /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($orderId)
    {
        if (Order::where([
            "id" => $orderId
        ])->exists()) {

            $order = Order::with('products', 'user')->where('id', $orderId)->first();

            if($order->user_id != auth()->user()->id) {
                return response()->json([
                    "status" => false,
                    "message" => "Invalid id for order",
                ], 403);
            }

            return response()->json([
                "status" => true,
                "message" => "Order with id $orderId found",
                "data" => $order
            ]);
        } else {

            return response()->json([
                "status" => false,
                "message" => "Product doesn't exist"
            ], 404);
        }
    }


        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_id)
    {
        if (Order::where([
            "id" => $order_id
        ])->exists()) {

            $order = Order::find($order_id);

            DB::table('order_product')->where('order_id', $order_id)->delete();

            $order->delete();

            return response()->json([
                "status" => true,
                "message" => "Order has been deleted"
            ]);
        }else{

            return response()->json([
                "status" => false,
                "message" => "Order with id $order_id doesn't exist."
            ], 404);
        }
    }
}
