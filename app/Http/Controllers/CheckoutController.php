<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'city' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'payment_method' => 'required|in:cod,momo'
            ]);

            $cart = Cart::first();
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn đang trống'
                ], 400);
            }

            // Tính tổng tiền
            $totalAmount = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // Thêm phí ship
            $totalAmount += 30000; // 30k phí ship

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'address' => $validated['address'] . ', ' . $validated['city'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'status' => 'pending',
                'payment' => $validated['payment_method'],
                'total' => $totalAmount,
                'voucher_code' => '',
                'sale_price' => 0,
                'pay_amount' => $totalAmount
            ]);

            // Tạo chi tiết đơn hàng
            $orderDetails = [];
            foreach ($cart->items as $item) {
                \DB::table('orderdetails')->insert([
                    'order_id' => $order->id,
                    'productvariant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $orderDetails[] = [
                    'product_name' => $item->variant->name ?? $item->variant->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity
                ];
            }

            // Lưu thông tin giỏ hàng trước khi xóa
            $cartInfo = [
                'cart_id' => $cart->id,
                'total_items' => $cart->items->count(),
                'total_amount' => $totalAmount
            ];

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->delete();

            // Chuẩn bị thông tin thanh toán Momo nếu cần
            $paymentInfo = null;
            if ($validated['payment_method'] === 'momo') {
                $paymentInfo = [
                    'partner_code' => 'MOMOBKUN20180529',
                    'access_key' => 'klm05TvNBzhg7h7j',
                    'secret_key' => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
                    'request_id' => time() . '',
                    'amount' => $totalAmount,
                    'order_id' => $order->order_code,
                    'order_info' => 'Thanh toán đơn hàng ' . $order->order_code,
                    'return_url' => route('checkout.success'),
                    'notify_url' => route('checkout.notify'),
                    'extra_data' => '',
                    'request_type' => 'captureMoMoWallet',
                    'signature' => hash_hmac('sha256',
                        "partnerCode=MOMOBKUN20180529&accessKey=klm05TvNBzhg7h7j&requestId=" . time() .
                        "&amount=" . $totalAmount . "&orderId=" . $order->order_code .
                        "&orderInfo=Thanh toán đơn hàng " . $order->order_code .
                        "&returnUrl=" . route('checkout.success') .
                        "&notifyUrl=" . route('checkout.notify') .
                        "&extraData=",
                        'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'
                    )
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_code' => $order->order_code,
                        'status' => $order->status,
                        'payment_method' => $order->payment,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'shipping_info' => [
                            'full_name' => $validated['full_name'],
                            'email' => $validated['email'],
                            'phone' => $validated['phone'],
                            'address' => $validated['address'],
                            'city' => $validated['city'],
                            'notes' => $validated['notes'] ?? null
                        ],
                        'amount' => [
                            'subtotal' => $totalAmount - 30000,
                            'shipping_fee' => 30000,
                            'total' => $totalAmount
                        ]
                    ],
                    'order_details' => $orderDetails,
                    'deleted_cart' => $cartInfo,
                    'payment_info' => $paymentInfo
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function failure()
    {
        return view('checkout.failure');
    }

    public function notify(Request $request)
    {
        try {
            $data = $request->all();
            \Log::info('Momo callback data: ' . json_encode($data));

            // Kiểm tra signature
            $signature = $data['signature'] ?? '';
            $expectedSignature = hash_hmac('sha256',
                "partnerCode={$data['partnerCode']}&accessKey={$data['accessKey']}&requestId={$data['requestId']}" .
                "&amount={$data['amount']}&orderId={$data['orderId']}&orderInfo={$data['orderInfo']}" .
                "&orderType={$data['orderType']}&transId={$data['transId']}&message={$data['message']}" .
                "&localMessage={$data['localMessage']}&responseTime={$data['responseTime']}" .
                "&errorCode={$data['errorCode']}&payType={$data['payType']}&extraData={$data['extraData']}",
                'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'
            );

            if ($signature !== $expectedSignature) {
                \Log::error('Invalid Momo signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            // Tìm đơn hàng
            $order = Order::where('order_code', $data['orderId'])->first();
            if (!$order) {
                \Log::error('Order not found: ' . $data['orderId']);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Cập nhật trạng thái đơn hàng
            if ($data['errorCode'] == 0) {
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'completed'
                ]);
                \Log::info('Order paid successfully: ' . $order->order_code);
            } else {
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);
                \Log::error('Payment failed for order: ' . $order->order_code);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Momo callback error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
