<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductVariationStoreRequest;
use App\Http\Requests\Product\ProductVariationUpdateRequest;
use App\Http\Resources\VariationResource;
use App\Models\Product;
use App\Models\Variation;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    use HttpResponses;
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductVariationStoreRequest $request, Product $product)
    {
        $variation = $product->variations()->create($request->only([
            'title',
            'type',
            'description',
            'price',
            'sku',
            'order',
        ]));

        $variation->stock()->create($request->only('quantity'));

        return $this->success(new VariationResource($variation), 'Product variation created successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductVariationUpdateRequest $request, Product $product, Variation $variation)
    {
        $this->authorize('update', $product);

        $variation->update($request->only([
            'title',
            'type',
            'description',
            'price',
            'sku',
            'order',
        ]));

        $variation->stock()->update($request->only([
            'quantity',
        ]));

        return $this->success(new VariationResource($variation), 'Product variation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Variation $variation)
    {
        $this->authorize('delete', $product);
        
        $variation->stock->delete();
        
        $variation->delete();

        return $this->success(null, 'Product variation deleted successfully');
    }
}
