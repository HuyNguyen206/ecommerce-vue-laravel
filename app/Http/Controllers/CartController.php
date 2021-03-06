<?php

namespace App\Http\Controllers;

use App\Cart\Cart;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;
    public function __construct(Cart $cart)
    {
        $this->middleware('auth');
        $this->cart = $cart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->cart->sync();
       return CartResource::make(\request()->user()->load(['cart.product', 'cart.stock', 'cart.type']))->additional([
           'meta' => $this->meta(request()),
       ]);
    }

    protected function meta(Request $request)
    {
        return [
             'is_empty' => $this->cart->isEmpty(),
             'subtotal' => $this->cart->subTotal()->formatted(),
             'total' => $this->cart->withShipping($request->shipping_method_id)->total()->formatted(),
             'changed' => $this->cart->isChanged()
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartStoreRequest $request)
    {
        $this->cart->add($request->products);
        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductVariation $productVariation, CartUpdateRequest $request)
    {
       $this->cart->update($productVariation->id, $request->quantity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVariation $productVariation)
    {
        $this->cart->destroy($productVariation->id);
    }
}
