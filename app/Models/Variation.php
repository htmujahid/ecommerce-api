<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'type',
        'description',
        'sku',
        'price',
        'order',
    ];

    protected static function booted()
    {
        static::addGlobalScope('stock', function ($builder) {
            $builder->with('stock', function ($builder) {
                $builder->select('variation_id', 'quantity');
            });
        });
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
