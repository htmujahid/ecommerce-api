<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('inventory')->paginate();

        return ProductsResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    public function store(ProductStoreRequest $request)
    {
        $product = Product::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'user_id' => auth()->user()->id, 
        ]);

        $product->inventory()->create($request->only(['price', 'quantity']));

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductsResource($product)
        ], 201);
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->only(['name', 'description']));

        $product->inventory()->update($request->only(['price', 'quantity']));

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductsResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->inventory()->delete();

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }

    public function storeImage(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $product->addMediaFromRequest('image')->toMediaCollection('images');

        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => new ProductsResource($product)
        ], 201);
    }
}
