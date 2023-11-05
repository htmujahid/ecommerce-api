<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialPostResource;
use App\Models\SocialPost;
use Illuminate\Http\Request;

class SocialPostController extends Controller
{
    public function index() 
    {
        $socialPosts = SocialPost::where('is_visible', true)->with('media')->get();

        return SocialPostResource::collection($socialPosts);
    }
}
