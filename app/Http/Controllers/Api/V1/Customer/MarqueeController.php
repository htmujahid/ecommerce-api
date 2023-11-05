<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarqueeResource;
use App\Models\Marquee;
use Illuminate\Http\Request;

class MarqueeController extends Controller
{
    public function index()
    {
        $marquee = Marquee::where('is_visible', true)->first();

        return new MarqueeResource($marquee);
    }
}
