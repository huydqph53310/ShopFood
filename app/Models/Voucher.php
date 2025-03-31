<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use LocalFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'sale_price',
        'min_order',
        'max_price',
        'quantity',
        'start_date',
        'end_date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
