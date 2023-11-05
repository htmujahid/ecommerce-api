<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Helper\Shipment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Jobs\OrderEmailJob;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use HttpResponses;

    public function store(StoreOrderRequest $request)
    {
        $items = $request->items;

        $customer = Customer::firstOrCreate([
            'email' => $request->email,
        ], [
            'name' => $request->name,
            'phone' => $request->phone,
        ]);
        
        $productVariants = ProductVariant::whereIn('id', array_column($items, 'variant_id'))->get()->keyBy('id');
 
        foreach ($items as &$item) {
            $variant = $productVariants[$item['variant_id']] ?? null;
            if ($variant) {
                $item['unit_price'] = $variant->price;
            }
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['unit_price'] * $item['qty'];
        }, $items));

        $shipmentPrice = Shipment::price($subtotal);

        $totalPrice = $subtotal + $shipmentPrice;

        $order = Order::create([
            'customer_id' => $customer->id,
            'number' => 'OR-' . random_int(100000, 999999),
            'total_price' => $totalPrice,
            'status' => 'new',
            'currency' => 'PKR',
            'shipping_price' => $shipmentPrice,
            'shipping_method' => 'flat',
        ]);

        $items = $order->items()->createMany($items);

        $order->address()->create([
            'street' => $request->street,
            'city' => $request->city,
            'country' => $request->country,
            'state' => $request->state,
            'zip' => $request->zip
        ]);

        foreach ($items as $item) {
            $variant = $productVariants[$item->variant_id];
            $variant->qty = $variant->qty - $item->qty;
            $variant->save();
        }

        // OrderEmailJob::dispatch('store.funncart@gmail.com');

        return $this->success(
            [
                'order' => $order,
                'items' => $items
            ], 
            'Order Created Successfully'
        );
    }
}
