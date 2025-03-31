<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use LocalFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'image',
        'description',
        'material',
        'instruct',
        'onpage',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
