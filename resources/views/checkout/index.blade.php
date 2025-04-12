@extends('layout')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Form thông tin -->
            <div class="bg-white p-8 rounded-xl">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Thông tin thanh toán</h2>
                <form id="checkoutForm" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                            <input type="text" name="full_name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="phone" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố</label>
                            <input type="text" name="city" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                            <input type="text" name="address" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Thanh toán khi nhận hàng (COD)</span>
                                </label>
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="payment_method" value="momo"
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700">Ví điện tử Momo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="bg-white p-8 rounded-xl">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Tóm tắt đơn hàng</h2>
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                    <div class="flex justify-between items-center py-3 border-b">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $item->variant->name ?? $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                        </div>
                        <p class="font-medium text-gray-800">{{ number_format($item->price * $item->quantity) }}đ</p>
                    </div>
                    @endforeach
                    <div class="pt-4 space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($cart->items->sum(function($item) { return $item->price * $item->quantity; })) }}đ</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Phí vận chuyển:</span>
                            <span>30,000đ</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-gray-800 pt-3 border-t">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($cart->items->sum(function($item) { return $item->price * $item->quantity; }) + 30000) }}đ</span>
                        </div>
                    </div>
                    <button type="button" onclick="submitCheckout()"
                        class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal hiển thị thông tin đơn hàng -->
<div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Thông tin đơn hàng</h3>
            <div class="mt-2 px-7 py-3">
                <div id="orderInfo" class="text-sm text-gray-500 text-left">
                    <!-- Thông tin đơn hàng sẽ được hiển thị ở đây -->
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function submitCheckout() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);

    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hiển thị thông tin đơn hàng trong modal
            const orderInfo = document.getElementById('orderInfo');
            orderInfo.innerHTML = `
                <p class="mb-2"><strong class="text-gray-700">Mã đơn hàng:</strong> ${data.order.id}</p>
                <p class="mb-2"><strong class="text-gray-700">Tổng tiền:</strong> ${data.order.total_amount.toLocaleString()}đ</p>
                <p class="mb-2"><strong class="text-gray-700">Trạng thái:</strong> ${data.order.status}</p>
                <p class="mb-4"><strong class="text-gray-700">Ngày đặt:</strong> ${data.order.created_at}</p>
                <div class="mt-4">
                    <p class="font-medium text-gray-700 mb-2">Chi tiết đơn hàng:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        ${data.order.details.map(detail => `
                            <li class="text-gray-600">${detail.product_name} x ${detail.quantity} - ${detail.price.toLocaleString()}đ</li>
                        `).join('')}
                    </ul>
                </div>
            `;

            // Hiển thị modal
            document.getElementById('orderModal').classList.remove('hidden');
        } else {
            alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
}

// Đóng modal khi click nút đóng
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('orderModal').classList.add('hidden');
    window.location.href = '{{ route("checkout.success") }}';
});
</script>
@endsection
