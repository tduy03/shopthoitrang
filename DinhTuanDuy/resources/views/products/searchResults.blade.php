@extends('layouts.app')

@section('content')
    <h1>Kết quả tìm kiếm cho: {{ request()->input('query') }}</h1>

    @if($products->isEmpty())
        <p>Không tìm thấy sản phẩm nào.</p>
    @else
        <ul>
            @foreach($products as $product)
                <li>{{ $product->name }} - {{ $product->category->name }} - {{ $product->category->name }}</li>

            @endforeach
        </ul>
    @endif
@endsection
