@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Welcome to Our Store</h1>

    @if(request()->has('query') && !$products->isEmpty())
        <h2>Kết quả tìm kiếm cho: "{{ request()->input('query') }}"</h2>
    @elseif(request()->has('query'))
        <p>Không tìm thấy sản phẩm nào phù hợp.</p>
    @endif

    <div class="card-deck">
        @foreach ($products as $product)
        <div class="card mb-4">
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 50%; object-fit: cover;">
            <div class="card-body text-center">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">{{ $product->description }}</p>
                <p class="card-text">Quantity: {{ $product->quantity }}</p>
                <p class="card-text">Price: ${{ number_format($product->price, 2) }}</p>
                <p class="card-text">Category: {{ optional($product->category)->name }}</p>
                <p class="card-text">Production Date: {{ $product->production_date ? \Carbon\Carbon::parse($product->production_date)->format('Y-m-d') : 'N/A' }}</p> <!-- Thêm ngày sản xuất -->
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Details</a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Hiển thị liên kết phân trang -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
