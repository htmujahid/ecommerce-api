<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_visible', true)
            ->orderBy('position', 'desc')
            ->with('media')
            ->withCount(['products' => function ($query) {
                $query->where('is_visible', true)->whereHas('productVariants');
            }])
            ->filter($request)->get();
            
        return CategoryResource::collection($categories);
    }
}
