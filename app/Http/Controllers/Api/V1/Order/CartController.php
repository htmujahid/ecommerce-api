<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartStoreRequest;
use App\Http\Requests\Order\CartUpdateRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = auth()->user()->carts()->with('variations')->get();

        return CartResource::collection($carts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartStoreRequest $request)
    {
        $cart = auth()->user()->carts()->firstOrCreate(
            ['user_id' => auth()->id()]
        );

        $cart->variations()->attach($request->variation_id, [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return $this->success(new CartResource($cart->load('variations')), 'Cart created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->variations()->delete();

        $cart->delete();

        return $this->success(null, 'Cart deleted successfully');
    }
}
