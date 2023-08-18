<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'description',
        'user_id'
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
}
