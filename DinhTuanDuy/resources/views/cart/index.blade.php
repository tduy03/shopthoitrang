@extends('layouts.app')

@section('content')
    <h1>Giỏ hàng của bạn</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($cart && count($cart) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                    <th>Danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr id="row-{{ $item['product']->id }}">
                        <td>
                            @if ($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}"
                                    alt="{{ $item['product']->name }}" style="max-width: 50px;">
                            @endif
                        </td>
                        <td>{{ $item['product']->name }}</td>
                        <td>
                            <button class="btn btn-warning btn-decrease"
                                data-product-id="{{ $item['product']->id }}">-</button>
                            <span id="quantity-{{ $item['product']->id }}">{{ $item['quantity'] }}</span>
                            <button class="btn btn-success btn-increase"
                                data-product-id="{{ $item['product']->id }}">+</button>
                        </td>
                        <td>{{ number_format($item['price'], 2) }} VND</td>
                        <td id="total-{{ $item['product']->id }}">
                            {{ number_format($item['price'] * $item['quantity'], 2) }} VND</td>
                        <td>{{ $item['product']->category->name }}</td>
                        <td>
                            <button class="btn btn-danger btn-remove"
                                data-product-id="{{ $item['product']->id }}">Xoá</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <h4>Tổng tiền: <span id="cart-total">{{ number_format($total, 2) }}</span> VND</h4>
        </div>

        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Họ tên</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <textarea name="address" id="address" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="payment_date">Ngày thanh toán</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="discount_code">Mã giảm giá</label>
                <input type="text" name="discount_code" id="discount_code" class="form-control">
            </div>
            <div class="form-group">
                <label for="payment_method">Phương thức thanh toán</label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Xác nhận đặt hàng</button>
        </form>
    @else
        <p>Giỏ hàng của bạn trống.</p>
    @endif
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý khi nhấn nút xóa sản phẩm
        document.querySelectorAll('.btn-remove').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let productId = this.dataset.productId;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                if (confirm('Bạn có chắc chắn muốn xoá sản phẩm này khỏi giỏ hàng?')) {
                    fetch(`/cart/remove/${productId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.cartTotal === '0.00') {
                                    document.querySelector('tbody').innerHTML =
                                        '<tr><td colspan="7">Giỏ hàng của bạn trống.</td></tr>';
                                    document.querySelector('#cart-total').innerText =
                                        '0.00 VND';
                                } else {
                                    document.querySelector(`#row-${productId}`).remove();
                                    document.querySelector('#cart-total').innerText = data
                                        .cartTotal + ' VND';
                                }
                                alert(data.message);
                            } else {
                                alert('Có lỗi xảy ra khi xoá sản phẩm.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });

        // Tăng số lượng sản phẩm
        document.querySelectorAll('.btn-increase').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let productId = this.dataset.productId;
                updateCart(productId, 'increase');
            });
        });

        // Giảm số lượng sản phẩm
        document.querySelectorAll('.btn-decrease').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let productId = this.dataset.productId;
                updateCart(productId, 'decrease');
            });
        });

        // Hàm cập nhật giỏ hàng
        function updateCart(productId, action) {
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/cart/update/${productId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`#quantity-${productId}`).innerText = data.quantity;
                        document.querySelector(`#total-${productId}`).innerText = data.total + ' VND';
                        document.querySelector('#cart-total').innerText = data.cartTotal + ' VND';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Hàm cập nhật tổng tiền
        function updateTotal() {
            const discountCode = document.getElementById('discount_code').value.trim();
            const paymentDate = new Date(document.getElementById('payment_date').value);
            const day = paymentDate.getDay(); // 0 (Chủ Nhật) đến 6 (Thứ Bảy)
            let total = parseFloat(document.getElementById('cart-total').innerText.replace(' VND', '').replace(
                ',', ''));

            // Kiểm tra mã giảm giá và ngày
            if (discountCode === 'HAVE11' && (day === 0 || day === 6)) {
                total *= 0.9; // Giảm 10%
            } else if (discountCode) {
                alert('Mã giảm giá không hợp lệ hoặc không áp dụng.');
            }

            // Cập nhật tổng tiền
            document.getElementById('cart-total').innerText = total.toFixed(2) + ' VND';
        }

        // Cập nhật tổng tiền khi chọn ngày thanh toán
        document.getElementById('payment_date').addEventListener('change', updateTotal);

        // Cập nhật tổng tiền khi nhập mã giảm giá
        document.getElementById('discount_code').addEventListener('input', updateTotal);
    });
</script>
