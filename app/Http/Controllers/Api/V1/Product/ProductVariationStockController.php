<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\VariationResource;
use App\Models\Variation;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductVariationStockController extends Controller
{
    use HttpResponses;
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Variation $variation)
    {
        $request->validate([
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        $variation->stock->update($request->input('quantity'));

        return $this->success(new VariationResource($variation), 'Product variation stock updated successfully');
    }
}
