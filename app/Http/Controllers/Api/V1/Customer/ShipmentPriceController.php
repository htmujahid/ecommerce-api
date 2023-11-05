<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Helper\Shipment;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ShipmentPriceController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $subtotal = $request->subtotal ?? 0;

        $shipmentPrice = Shipment::price($subtotal);

        return $this->success(
            $shipmentPrice,
            'Shipment price retrieved successfully'
        );

    }
}
