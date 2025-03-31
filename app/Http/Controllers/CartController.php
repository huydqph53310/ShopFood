<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::first();
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = Cart::first();
            if (!$cart) {
                $cart = Cart::create(['total_amount' => 0]);
            }

            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $request->quantity
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price
                ]);
            }

            $this->updateCartTotal($cart);

            return redirect()->back()->with('success', 'Product added to cart successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add product to cart');
        }
    }

    public function update(Request $request, Cart $cart)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $request->quantity]);
                $this->updateCartTotal($cart);
            }

            return redirect()->back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update cart');
        }
    }

    public function remove(Cart $cart, Product $product)
    {
        try {
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            if ($cartItem) {
                $cartItem->delete();
                $this->updateCartTotal($cart);
            }

            return redirect()->back()->with('success', 'Product removed from cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove product from cart');
        }
    }

    public function removeMultiple(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*' => 'required|integer'
            ]);

            $cart = Cart::first();
            $itemIds = $request->input('items', []);

            $cart->items()->whereIn('id', $itemIds)->delete();
            $this->updateCartTotal($cart);

            return redirect()->route('cart.index')->with('success', 'Selected items removed from cart.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove selected items. Please try again.');
        }
    }

    public function checkout(Cart $cart)
    {
        if ($cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        // Here you would typically handle the checkout process
        // For now, we'll just clear the cart
        $cart->items()->delete();
        $cart->update(['total_amount' => 0]);

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }

    private function updateCartTotal($cart)
    {
        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->update(['total_amount' => $total]);
    }
}
