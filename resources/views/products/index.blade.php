@extends('layout')

@section('title', 'Our Products')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Search and Filter Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Search & Filter</h5>
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search products...">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Our Products</h2>
                <div class="text-muted">
                    {{ $products->count() }} products found
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($product->image)
                            <img src="{{ asset('assets/img'.'/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text"><strong>Price: ${{ number_format($product->price, 2) }}</strong></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('products.detail', $product) }}" class="btn btn-outline-primary">View Details</a>
                                <form action="{{ route('cart.store', $product) }}" method="POST" class="d-inline add-to-cart-form">
                                    @csrf
                                    @if($product->variants->isNotEmpty())
                                        <input type="hidden" name="variant_id" value="{{ $product->variants->first()->id }}">
                                        <div class="input-group" style="max-width: 300px;">
                                            <input type="number" name="quantity" class="form-control" value="1" min="1">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-cart-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            Sản phẩm chưa có biến thể
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No products found matching your criteria.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        // Update cart count
                        $('.cart-count').text(response.cart_count);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        });
    });
</script>
@endpush
@endsection
