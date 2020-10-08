<?php

namespace App\Observers;

use App\Cart;
use Illuminate\Support\Facades\Auth;

class CartObserver
{
    /**
     * Handle the cart "created" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function creating(Cart $cart)
    {
        //
        if (Auth::check()) {
            $cart->user_id = Auth::id();
        }
    }

    /**
     * Handle the cart "updated" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function updating(Cart $cart)
    {
        //
        if (Auth::check()) {
            $cart->user_id = Auth::id();
        }
    }

}
