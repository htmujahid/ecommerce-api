<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'city',
        'country',
        'state',
        'zip',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
