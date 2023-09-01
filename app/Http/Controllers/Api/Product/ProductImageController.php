<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    use HttpResponses;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'image' => ['required', 'image', 'max:2048']
        ]);

        $product->addMediaFromRequest('image')->toMediaCollection('images');

        return $this->success(new ProductResource($product), 'Product updated successfully', 200);
    }
}
