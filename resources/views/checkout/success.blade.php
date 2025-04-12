@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success display-1 mb-4"></i>
                    <h2 class="card-title mb-4">Thanh toán thành công!</h2>
                    <p class="card-text mb-4">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được xác nhận.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">Xem đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
