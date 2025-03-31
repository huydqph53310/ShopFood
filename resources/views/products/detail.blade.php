@extends('layout')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('assets/img'.'/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
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

            <div class="mb-4">
                <h3 class="text-danger">${{ number_format($product->price, 2) }}</h3>
            </div>

            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                @csrf
                <div class="input-group" style="max-width: 300px;">
                    <input type="number" name="quantity" class="form-control" value="1" min="1">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <ul class="list-unstyled">
                        <li><strong>Category:</strong> {{ $product->category->name }}</li>
                        <li><strong>Price:</strong> ${{ number_format($product->price, 2) }}</li>
                        <li><strong>Created:</strong> {{ $product->created_at->format('M d, Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Related Products</h2>
        </div>
        @foreach($product->category->products->where('id', '!=', $product->id)->take(3) as $relatedProduct)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($relatedProduct->image)
                    <img src="{{ asset('assets/img'.'/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                    <p class="card-text">{{ Str::limit($relatedProduct->description, 100) }}</p>
                    <p class="card-text"><strong>Price: ${{ number_format($relatedProduct->price, 2) }}</strong></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.detail', $relatedProduct) }}" class="btn btn-outline-primary">View Details</a>
                        <form action="{{ route('cart.add', $relatedProduct) }}" method="POST" class="d-inline">
                            @csrf
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
