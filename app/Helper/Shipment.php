<?php
namespace App\Helper;

use App\Models\OrderShipment;

class Shipment {
    public static function price($subtotal)
    {
        $orderShipment = OrderShipment::first();

        $shipmentPrice = $orderShipment->price;

        $isThreshold = $orderShipment->is_threshold;

        $shipmentThresholdPrice = $orderShipment->threshold_price;

        if ($subtotal >= $shipmentThresholdPrice && $isThreshold) {
            $shipmentPrice = 0;
        } 

        return $shipmentPrice;
    }
}