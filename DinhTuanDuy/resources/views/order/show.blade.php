@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<h1>Chi tiết đơn hàng #{{ $order->id }}</h1>

<p>Tên: {{ $order->name }}</p>
<p>Email: {{ $order->email }}</p>
<p>Địa chỉ: {{ $order->address }}</p>
<p>Số điện thoại: {{ $order->phone }}</p>
<p>Phương thức thanh toán: {{ $order->payment_method }}</p>
<p>Tổng tiền: {{ number_format($order->total_amount, 2) }} VNĐ</p>
<p>Thời gian đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>

<h2>Thông tin sản phẩm</h2>
<ul class="list-group">
    @if($order->products && $order->products->isNotEmpty())
        @foreach($order->products as $product)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 50px; margin-right: 10px;">
                    @endif
                    <span>{{ $product->name }} - {{ $product->pivot->quantity }} x {{ number_format($product->price, 2) }} VNĐ</span>
                </div>
            </li>
        @endforeach
    @else
        <li class="list-group-item">Không có sản phẩm nào trong đơn hàng.</li>
    @endif
</ul>

<!-- Nút in hóa đơn -->
<button onclick="window.print()" class="btn btn-primary mt-4">In hóa đơn</button>
@endsection
