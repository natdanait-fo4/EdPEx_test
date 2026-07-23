<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plo extends Model
{
    use HasFactory;

    protected $fillable = [
        'degree_level',
        'title',
        'description',
        'icon_class',
        'image_path',
        'is_active',
        'order_index',
    ];
}
