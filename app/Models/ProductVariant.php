<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table = 'productvariants';
    protected $fillable = [
        'product_id',
        'size_id',
        'topping_id',
        'price',
        'sale',
        'stock',
        'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function topping()
    {
        return $this->belongsTo(Topping::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
