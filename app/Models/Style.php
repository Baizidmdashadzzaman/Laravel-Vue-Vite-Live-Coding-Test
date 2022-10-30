<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Buyer;
use App\Models\StyleItem;

class Style extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyers_id',
        'style_number',
        'color'
    ];

    public function buyer() {
        return $this->hasOne(Buyer::class,'id','buyers_id');
    }
    public function items()
    {
        return $this->hasMany(StyleItem::class, 'style_id');
    }
}
