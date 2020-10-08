<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\User;
use App\Product;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user=$request->user();
        return view('order.index', compact('user') );
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::transaction(function () use ($request) {
            
            // 建立新訂單
            $order=new Order;
            $order->address = $request->address;
            $order->total=0;
            $order->closed=0;
            $order->user_id=$request->user()->id;
            $order->save();
           
            $total=0;

            // 計算購物車的內容、數量、價格
            foreach($request->amount as $product_id => $amount){
                       $product=Product::find($product_id);
                       $item=new OrderItem;
                       $item->order_id=$order->id;
                       $item->product_id=$product_id;
                       $item->amount=$amount;
                       $item->price=$product->price;
                       $item->save();
                       $total=$total+$product->price * $amount;

            }
            //更新訂單總金額
            $order->total=$total;
            $order->update();

            // 從購物車移除下單商品
            $request->user()->cart()->delete();
        });
        return redirect()->route('order.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
