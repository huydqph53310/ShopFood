<?php

namespace App\Models;

use Carbon\Traits\LocalFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use LocalFactory;
    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'email',
        'status',
        'payment',
        'total',
        'voucher_code',
        'sale_price',
        'pay_amount',
    ];
}
