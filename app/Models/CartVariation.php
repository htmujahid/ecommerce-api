<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartVariation extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'variation_id', 'quantity'];
}
