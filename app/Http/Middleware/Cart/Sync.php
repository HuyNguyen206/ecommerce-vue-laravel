<?php

namespace App\Http\Middleware\Cart;

use App\Cart\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Sync
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->cart->sync();
        if ($this->cart->isChanged()) {
            return response()->json([
                'message' => 'Oh no, some items in your cart have changed. Please review these changes before placing your order.'
            ], 409);
        }
        return $next($request);
    }
}
