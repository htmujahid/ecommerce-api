<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CategoryStatusController extends Controller
{
    use HttpResponses;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Category $category)
    {        
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);
        
        $category->update($request->only(['status']));

        return $this->success(new CategoryResource($category), 'Category status updated successfully');
    }
}
