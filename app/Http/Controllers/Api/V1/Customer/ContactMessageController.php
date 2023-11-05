<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    use HttpResponses;

    public function store(StoreContactMessageRequest $request)
    {
        $contact = ContactMessage::create($request->validated());

        return $this->success($contact, 'Contact message sent successfully');
    }
}
