@extends('layout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Giỏ hàng</h1>

    @if($cart->items->isEmpty())
        <div class="alert alert-info">
            Giỏ hàng của bạn đang trống.
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Topping</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                        <tr>
                            <td>
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                {{ $item->product->name ?? 'N/A' }}
                            </td>
                            <td>{{ $item->variant->size->name ?? 'N/A' }}</td>
                            <td>{{ $item->variant->topping->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price) }}đ</td>
                            <td>
                                <div class="input-group" style="width: 150px;">
                                    <button class="btn btn-outline-secondary decrease"
                                            type="button"
                                            data-cart-item-id="{{ $item->id }}">-</button>
                                    <input type="number"
                                           class="form-control text-center quantity"
                                           value="{{ $item->quantity }}"
                                           min="1"
                                           data-cart-item-id="{{ $item->id }}">
                                    <button class="btn btn-outline-secondary increase"
                                            type="button"
                                            data-cart-item-id="{{ $item->id }}">+</button>
                                </div>
                            </td>
                            <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                            <td>
                                <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td colspan="2"><strong>{{ number_format($cart->total_amount) }}đ</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Tổng cộng: <span class="text-danger">{{ number_format($cart->items->sum(function($item) { return $item->price * $item->quantity; }) + 30000) }}đ</span></h5>
                    <small class="text-muted">(Đã bao gồm phí ship 30.000đ)</small>
                </div>
                <form id="checkout-form" action="{{ route('cart.checkout', $cart) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Thanh toán
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        function updateQuantity(cartItemId, quantity) {
            $.ajax({
                url: `/cart/update/${cartItemId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra');
                }
            });
        }

        $('.decrease').click(function() {
            let cartItemId = $(this).data('cart-item-id');
            let input = $(`input[data-cart-item-id="${cartItemId}"]`);
            let current = parseInt(input.val());
            if (current > 1) {
                updateQuantity(cartItemId, current - 1);
            }
        });

        $('.increase').click(function() {
            let cartItemId = $(this).data('cart-item-id');
            let input = $(`input[data-cart-item-id="${cartItemId}"]`);
            let current = parseInt(input.val());
            updateQuantity(cartItemId, current + 1);
        });

        $('.quantity').change(function() {
            let cartItemId = $(this).data('cart-item-id');
            let quantity = parseInt($(this).val());
            if (quantity < 1) {
                quantity = 1;
                $(this).val(1);
            }
            updateQuantity(cartItemId, quantity);
        });

        $('.update-quantity').click(function() {
            const itemId = $(this).data('id');
            const quantity = $(this).siblings('input').val();

            $.ajax({
                url: `/cart/${itemId}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
                }
            });
        });

        // Xử lý form thanh toán
        $('#checkout-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    alert(response?.message || 'Có lỗi xảy ra khi thanh toán');
                }
            });
        });
    });
</script>
@endpush
