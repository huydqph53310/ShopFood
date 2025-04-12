@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-x-circle-fill text-danger display-1 mb-4"></i>
                    <h2 class="card-title mb-4">Thanh toán thất bại!</h2>
                    <p class="card-text mb-4">Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary">Quay lại giỏ hàng</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
