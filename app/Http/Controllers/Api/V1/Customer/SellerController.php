<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\SellerResource;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $sellers = Seller::with('media')->filter($request)->get();
        
        return SellerResource::collection($sellers);   
    }

    public function show(Seller $seller)
    {
        return new SellerResource([$seller->load('media')]);
    }
}