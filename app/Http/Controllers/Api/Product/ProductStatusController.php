<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductStatusController extends Controller
{
    use HttpResponses;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);
        
        $product->update($request->only(['status']));

        return $this->success(new ProductResource($product), 'Product status updated successfully');
    }
}
