<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StyleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'style_id',
        'metarial_number',
        'width_number',
        'unit',
        'size'
    ];

}
