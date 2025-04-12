@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            <p class="text-muted mb-4">{{ $product->description }}</p>

            @if($product->variants->isNotEmpty())
                <div class="mb-4">
                    <h4>Chọn size:</h4>
                    <div class="btn-group" role="group">
                        @foreach($product->variants as $variant)
                            <button type="button" class="btn btn-outline-primary size-btn"
                                    data-variant-id="{{ $variant->id }}"
                                    data-price="{{ $variant->price }}">
                                {{ $variant->size->name }} - {{ number_format($variant->price) }}đ
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <h4>Chọn topping:</h4>
                    <div class="btn-group" role="group">
                        @foreach($product->variants as $variant)
                            <button type="button" class="btn btn-outline-secondary topping-btn"
                                    data-variant-id="{{ $variant->id }}"
                                    data-topping-price="{{ $variant->topping->price }}">
                                {{ $variant->topping->name }} - {{ number_format($variant->topping->price) }}đ
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <h4>Số lượng:</h4>
                    <div class="input-group" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease">-</button>
                        <input type="number" class="form-control text-center" value="1" min="1" id="quantity">
                        <button class="btn btn-outline-secondary" type="button" id="increase">+</button>
                    </div>
                </div>

                <div class="mb-4">
                    <h4>Tổng cộng: <span id="total-price">0</span>đ</h4>
                </div>

                <button class="btn btn-primary btn-lg" id="add-to-cart">
                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                </button>
            @else
                <div class="alert alert-warning">
                    Sản phẩm hiện chưa có biến thể. Vui lòng quay lại sau.
                </div>
            @endif
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="row mt-5">
            <div class="col-12">
                <h3>Sản phẩm liên quan</h3>
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                    <p class="card-text">{{ number_format($relatedProduct->variants->first()->price) }}đ</p>
                                    <a href="{{ route('products.detail', $relatedProduct->id) }}" class="btn btn-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        let selectedVariantId = null;
        let selectedToppingId = null;
        let quantity = 1;
        let basePrice = 0;
        let toppingPrice = 0;

        // Xử lý chọn size
        $('.size-btn').click(function() {
            $('.size-btn').removeClass('active');
            $(this).addClass('active');
            selectedVariantId = $(this).data('variant-id');
            basePrice = $(this).data('price');
            updateTotal();
        });

        // Xử lý chọn topping
        $('.topping-btn').click(function() {
            $('.topping-btn').removeClass('active');
            $(this).addClass('active');
            selectedToppingId = $(this).data('variant-id');
            toppingPrice = $(this).data('topping-price');
            updateTotal();
        });

        // Giảm số lượng
        $('#decrease').click(function() {
            let current = parseInt($('#quantity').val());
            if (current > 1) {
                $('#quantity').val(current - 1);
                quantity = current - 1;
                updateTotal();
            }
        });

        // Tăng số lượng
        $('#increase').click(function() {
            let current = parseInt($('#quantity').val());
            $('#quantity').val(current + 1);
            quantity = current + 1;
            updateTotal();
        });

        // Thay đổi số lượng trực tiếp
        $('#quantity').change(function() {
            quantity = parseInt($(this).val());
            if (quantity < 1) {
                quantity = 1;
                $(this).val(1);
            }
            updateTotal();
        });

        // Cập nhật tổng giá
        function updateTotal() {
            let total = (basePrice + toppingPrice) * quantity;
            $('#total-price').text(total.toLocaleString() + 'đ');
        }
    });
</script>
@endpush
