<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::whereHas('productVariants')
            ->where('is_visible', true)
            ->with('media', 'productVariants')
            ->withSum('productVariants', 'qty')
            ->filter($request)->get();
        
        return ProductResource::collection($products);   
    }

    public function show(Product $product)
    {
        $data = $product
            ->load([
                'media', 
                'productVariants' => function($query) {
                    $query->with('media');
                }
            ])
            ->loadSum('productVariants', 'qty');

        return new ProductResource($data);
    }
}
