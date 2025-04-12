<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::first();
        if (!$cart) {
            $cart = Cart::create(['total_amount' => 0]);
        }

        // Load relationships to avoid null errors
        $cart->load(['items.product', 'items.variant.size', 'items.variant.topping']);

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'variant_id' => 'required|exists:productvariants,id'
            ]);

            $variant = ProductVariant::findOrFail($request->variant_id);
            if (!$variant) {
                return redirect()->back()->with('error', 'Biến thể sản phẩm không tồn tại');
            }

            $product = $variant->product;
            if (!$product) {
                return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
            }

            $cart = Cart::first();
            if (!$cart) {
                $cart = Cart::create(['total_amount' => 0]);
            }

            $cart->addItem($product->id, $variant->id, $request->quantity, $variant->price);

            return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
                ], 404);
            }

            $cartItem->update(['quantity' => $request->quantity]);
            $this->updateCartTotal($cartItem->cart);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(CartItem $cartItem)
    {
        try {
            // Xóa cartItem
            $cartItem->delete();

            // Cập nhật tổng tiền của giỏ hàng
            $cart = Cart::first();
            $this->updateCartTotal($cart);

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi xóa sản phẩm khỏi giỏ hàng: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage()
            ], 500);
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
        try {
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
            }

            // Lưu thông tin giỏ hàng vào session
            $cartData = [];
            foreach ($cart->items as $item) {
                $cartData[] = [
                    'product_id' => $item->product_variant_id,
                    'product_variant_id' => $item->product_variant_id,
                    'name' => $item->variant->name ?? $item->variant->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->variant->image ?? $item->variant->product->image
                ];
            }

            // Xóa session cart cũ nếu có
            session()->forget('cart');

            // Lưu thông tin giỏ hàng mới vào session
            session()->put('cart', $cartData);
            session()->save();

            return redirect()->route('checkout.index');

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function updateCartTotal($cart)
    {
        $total = $cart->items()->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $cart->update(['total_amount' => $total]);
    }
}
