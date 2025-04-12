<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addItem($productId, $variantId, $quantity, $price)
    {
        $item = $this->items()->where('product_variant_id', $variantId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $this->items()->create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
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
