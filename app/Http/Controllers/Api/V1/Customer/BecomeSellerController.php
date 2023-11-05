<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreBecomeSellerRequest;
use App\Models\BecomeSeller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class BecomeSellerController extends Controller
{
    use HttpResponses;

    public function store(StoreBecomeSellerRequest $request)
    {
        $seller = BecomeSeller::create($request->validated());

        return $this->success($seller, 'Seller request sent successfully');
    }
}
