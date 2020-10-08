<?php

namespace App\Http\Controllers;

use DB;
use App\Cart;
use App\User;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Alert;
use App\Http\Requests\CartRequest;
use App\Observers\CartObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // 如果是訪客 抓取session request
        if(!Auth::check()){
            $request_product=session()->get('request');
            $check=session()->has('request');
            $product=[];
            // 如果訪客加入購物車
            if($check){
            foreach($request_product as $key => $value){
                $get_product=Product::where('id',$key)->first();
                $product[$key]=$get_product;
                $product[$key]['amount']=$value['amount'];  
            }
                     }
            //  儲存購物車session        
            session()->put('cart',$product);
            return view('cart.guest',compact('product'));
        // 如果會員已登入    
        }else{
            // 檢查登入前有無商品在購物車 若有便加入舊購物車
            // 若無便新增購物車
           if(session()->has('cart')){
              $product=session()->pull('cart');
              foreach ($product as $key => $value) {
                  if($cart=$request->user()->cart()->where('product_id',$key)->first()){
                   $cart->update([
                    'amount'=>$cart->amount+$value['amount']
                   ]);
                  }else{
                    $cart=new Cart;
                    $cart->user_id=$request->user()->id;
                    $cart->product_id=$value['id'];
                    $cart->amount=$value['amount'];
                    $cart->save();     
                  }
                 
                }
            $user = $request->user();
            return view('cart.index' , compact('user'));
           }else{
            $user = $request->user();
            return view('cart.index' , compact('user'));
           }           
        }
      
        
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
     public function store(CartRequest $request)
    {
//  會員登入     
  if(Auth::check()){
   if($cart = $request->user()->cart()->where('product_id',$request->product_id)->first()){
            $cart->update([
                'amount'=> $cart->amount + $request->amount
            ]);
         }else{
             Cart::create($request->all());
         }  
    
// 訪客登入 加入購物資料到session
  }else{
    $product_id=$request->product_id;
    $request_product=session()->get("request.$product_id.product_id");
    
            if($product_id==$request_product){
            session()->put("request.$product_id.amount",
            session()->get("request.$product_id.amount")+$request->amount
            );
            
            }else{
           session()->put("request.$request->product_id",
          ['product_id'=>$request->product_id,
           'amount'=>$request->amount
        ]);
            }
    }
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id, Request $request)
    {
        //
        if(Auth::check()){
            $request->user()->cart()->where('product_id',$product_id)->delete();
            return [];

        }else{
            session()->forget("request.$product_id");

        }
    }
}
