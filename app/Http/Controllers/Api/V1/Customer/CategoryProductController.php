<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function index(Request $request, Category $category)
    {
        $products = $category->products()
            ->where('is_visible', true)
            ->whereHas('productVariants')
            ->with('media', 'productVariants')
            ->withSum('productVariants', 'qty')
            ->get();
        
        return ProductResource::collection($products);   
    }
}
