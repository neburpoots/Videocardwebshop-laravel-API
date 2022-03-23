<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $fillable = [
        'name',
        'image',
        'price',
    ];
    
    use HasFactory;

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product', 'product_id', 'order_id')
        ->withPivot('quantity');
    }
}
