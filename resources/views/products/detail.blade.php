@extends('layout')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('assets/img/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>

            <div class="mb-4">
                <span class="badge bg-primary">{{ $product->category->name }}</span>
            </div>

            <p class="lead mb-4">{{ $product->description }}</p>

            <!-- Product Variants -->
            <div class="mb-4">
                <h4>Variants</h4>
                <div class="list-group">
                    @foreach($product->variants as $variant)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $variant->name }}</h5>
                                    <p class="mb-1 text-muted">Stock: {{ $variant->stock }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="mb-1 text-danger">${{ number_format($variant->price, 2) }}</p>
                                    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Số lượng</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <h3 class="mb-4">Related Products</h3>
        @foreach($relatedProducts as $relatedProduct)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($relatedProduct->image)
                    <img src="{{ asset('assets/img/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                    <p class="card-text">{{ Str::limit($relatedProduct->description, 100) }}</p>
                    <p class="card-text"><strong>Price: ${{ number_format($relatedProduct->price, 2) }}</strong></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.detail', $relatedProduct) }}" class="btn btn-outline-primary">View Details</a>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="variant_id" value="{{ $relatedProduct->variants->first()->id }}">
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" style="max-width: 80px;">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
