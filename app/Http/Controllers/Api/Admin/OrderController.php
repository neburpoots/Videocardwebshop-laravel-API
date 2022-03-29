<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

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

        $orders = Order::with('products')->get();

        return response()->json([
            "status" => true,
            "message" => "All orders for $name",
            "data" => $orders
        ]);
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

            $order->delete();

            $order->product()->detach();

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
