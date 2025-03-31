<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    protected $fillable = [
        'total_amount'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addItem($productId, $quantity, $price)
    {
        $item = $this->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        $this->updateTotalAmount();
    }

    public function updateTotalAmount()
    {
        $this->total_amount = $this->items()->sum(DB::raw('quantity * price'));
        $this->save();
    }

    public function clear()
    {
        $this->items()->delete();
        $this->updateTotalAmount();
    }
}
