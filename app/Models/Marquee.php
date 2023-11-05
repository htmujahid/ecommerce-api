<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'background_color',
        'text_color',
        'is_visible',
    ];
}
