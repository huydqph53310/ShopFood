<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    use LocalFactory;

    protected $fillable = [
        'name',
        'price',
    ];
    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class);
    }
}
