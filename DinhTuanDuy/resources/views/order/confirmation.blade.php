@extends('layouts.app')

@section('content')
<h1>Xác Nhận Đơn Hàng</h1>

<h4>Thông tin người đặt:</h4>
<p>Tên: {{ $order->name }}</p>
<p>Email: {{ $order->email }}</p>
<p>Số điện thoại: {{ $order->phone }}</p>
<p>Địa chỉ: {{ $order->address }}</p>
<p>Phương thức thanh toán: {{ $order->payment_method }}</p>
<p>Tổng tiền: {{ number_format($order->total_amount, 2) }} VND</p>

<h4>Thông tin sản phẩm:</h4>
<ul class="list-group">
    @foreach ($order->items as $item)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                @if ($item->product->image)
                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="max-width: 50px; margin-right: 10px;">
                @endif
                {{ $item->product->name }} - Số lượng: {{ $item->quantity }} - Giá: {{ number_format($item->price, 2) }} VND
            </div>
        </li>
    @endforeach
</ul>

<button class="btn btn-primary mt-4" onclick="window.print()">In hóa đơn</button>
@endsection
