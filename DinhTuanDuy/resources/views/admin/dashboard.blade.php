{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Chào mừng đến với trang quản trị! Tại đây, bạn có thể quản lý sản phẩm, danh mục, và nhiều chức năng khác.</p>
    <div class="mt-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Quản lý Sản phẩm</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quản lý Danh mục</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-success">Quản lý Đơn hàng</a> <!-- Nút quản lý đơn hàng -->
    </div>
</div>
@endsection
