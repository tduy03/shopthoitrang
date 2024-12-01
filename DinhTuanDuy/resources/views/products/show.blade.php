@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-4" style="max-width: 200px;">
    @endif
    <p>{{ $product->description }}</p>
    <p>Quantity: {{ $product->quantity }}</p>
    <p>Price: ${{ number_format($product->price, 2) }}</p>
    <p>Category: {{ $product->category->name }}</p>
    <p>Production Date: {{ $product->production_date ? \Carbon\Carbon::parse($product->production_date)->format('Y-m-d') : 'N/A' }}</p> <!-- Thêm ngày sản xuất -->

    <a href="{{ route('welcome') }}" class="btn btn-secondary">Quay lại danh sách</a>

    @auth
        <!-- Form để thêm sản phẩm vào giỏ hàng -->
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
        </form>
    @else
        <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để thêm sản phẩm vào giỏ hàng.</p>
    @endauth
</div>
@endsection
