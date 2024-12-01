<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác Nhận Đơn Hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        img {
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .btn {
            background-color: #007bff;
            /* Màu xanh dương */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
            /* Màu xanh đậm khi hover */
        }
    </style>
</head>

<body>
    <h1>Cảm ơn bạn đã đặt hàng!</h1>
    <p>Thông tin đơn hàng của bạn:</p>
    <p>Mã đơn hàng: {{ $order->id }}</p>
    <p>Tên: {{ $order->name }}</p>
    <p>Email: {{ $order->email }}</p>
    <p>Địa chỉ: {{ $order->address }}</p>
    <p>Tổng số tiền: {{ number_format($order->total_amount, 2) }} VNĐ</p>

    <h2>Thông tin sản phẩm đã đặt:</h2>
    <ul>
        @foreach ($order->items as $item)
            <li>
                @if ($item->product->image)
                    <img src="{{ url('storage/' . $item->product->image) }}"
                        alt="{{ $item->product->name }}" style="max-width: 50px; margin-right: 10px;">
                @endif
                {{ $item->product->name ?? 'Sản phẩm không xác định' }} -
                Số lượng: <strong>{{ $item->quantity }}</strong> -
                Giá: <strong>{{ number_format($item->price, 2) }} VNĐ</strong>
            </li>
        @endforeach
    </ul>

    <p>Cảm ơn bạn đã mua hàng!</p>

    <!-- Nút xác nhận đơn hàng -->
    <a href="{{ route('orders.confirm', $order->id) }}" class="btn">Xác Nhận Đơn Hàng</a>
</body>

</html>
