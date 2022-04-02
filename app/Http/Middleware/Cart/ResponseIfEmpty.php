<?php

namespace App\Http\Middleware\Cart;

use App\Cart\Cart;
use Closure;
use Illuminate\Http\Request;

class ResponseIfEmpty
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
        if ($this->cart->isEmpty()) {
            abort(409,'Please order at least one product with quantity');
        }
        return $next($request);
    }
}
