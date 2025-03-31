@extends('layout')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Shopping Cart</h2>

    @if($cart && $cart->items->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-4">
                                    @if($item->product->image)
                                        <img src="{{ asset('assets/img/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="img-fluid rounded"
                                             style="width: 100%; height: 300px; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h5 class="mb-2">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-2">{{ $item->product->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="input-group" style="max-width: 150px;">
                                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                <button type="submit" class="btn btn-outline-secondary" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                            </form>
                                            <input type="number" class="form-control text-center" value="{{ $item->quantity }}" readonly>
                                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                <button type="submit" class="btn btn-outline-secondary">+</button>
                                            </form>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-0">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                            <form action="{{ route('cart.remove', [$cart, $item->product]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($cart->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%)</span>
                            <span>${{ number_format($cart->total_amount * 0.1, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong>${{ number_format($cart->total_amount * 1.1, 2) }}</strong>
                        </div>
                        <form action="{{ route('cart.checkout', $cart) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Proceed to Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted">Add some products to your cart and they will appear here</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @endif
</div>
@endsection
