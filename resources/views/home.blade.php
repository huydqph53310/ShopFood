@extends('layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to Our Restaurant</h1>
                <p class="lead mb-4">Discover our delicious menu and enjoy the best dining experience.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">View Menu</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <h2 class="text-center mb-4">Our Categories</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-200">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->description }}</p>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary">View Products</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('assets/img'.'/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 350px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text"><strong>Price: ${{ number_format($product->price, 2) }}</strong></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.detail', $product) }}" class="btn btn-outline-primary">View Details</a>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
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
</section>
@endsection
