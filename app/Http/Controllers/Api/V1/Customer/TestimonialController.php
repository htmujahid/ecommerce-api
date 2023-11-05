<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index() 
    {
        $testimonials = Testimonial::where('is_visible', true)->with('media')->get();

        return TestimonialResource::collection($testimonials);
    }
}
