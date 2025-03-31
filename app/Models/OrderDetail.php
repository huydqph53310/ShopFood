<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use LocalFactory;

    protected $fillable = [
        'order_id',
        'productvariant_id',
        'price',
        'quantity',
    ];
}
