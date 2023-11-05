<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerProductController extends Controller
{
    public function index(Request $request, Seller $seller)
    {
        $products = $seller->products()
            ->where('is_visible', true)
            ->with('media')
            ->withSum('productVariants', 'qty')
            ->get();
        
        return ProductResource::collection($products);   
    }
}
