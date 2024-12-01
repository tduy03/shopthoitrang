@extends('layouts.app')

@section('title', 'Danh Sách Đơn Hàng')

@section('content')
    <h1>Danh Sách Đơn Hàng</h1>

    @if($orders->isEmpty())
        <p>Không có đơn hàng nào.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Thời gian đặt</th>
                    <th>Tổng tiền</th>
                    <th>Vận chuyển</th>
                    <th>Trạng thái</th> <!-- Cột trạng thái xác nhận -->
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ number_format($order->total_amount, 2) }} VNĐ</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->is_confirmed ? 'Đã xác nhận' : 'Chưa xác nhận' }}</td> <!-- Hiển thị trạng thái xác nhận -->
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-info">Xem</a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                            <form action="{{ route('orders.updateStatus', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
