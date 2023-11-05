<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCommentResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function index(Product $product)
    {
        return ProductCommentResource::collection($product->load(['comments' => function($query){
            return $query->with('customer')->get();
        }])->comments);
    }
}
