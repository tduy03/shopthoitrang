<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng của người dùng
    public function index()
    {
        // Lấy tất cả các đơn hàng của người dùng hiện tại
        $orders = Order::where('user_id', Auth::id())->get();
        return view('order.index', compact('orders'));
    }

    public function placeOrder(Request $request)
    {
        // Tạo đơn hàng mới
        $order = new Order();
        $order->user_id = Auth::id();
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->payment_method = $request->payment_method;
        $order->total_amount = $request->total_amount; // Tổng số tiền của giỏ hàng
        $order->save();

        // Lưu thông tin sản phẩm trong giỏ hàng vào bảng order_items
        foreach (session('cart') as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product']->id;
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->save();
        }

        // Gửi email xác nhận
        Mail::to($order->email)->send(new OrderConfirmationMail($order));

        // Xóa giỏ hàng sau khi đặt hàng
        session()->forget('cart');

        // Điều hướng đến trang xác nhận đơn hàng
        return redirect()->route('order.confirmation', ['order' => $order->id]);
    }

    public function confirmation(Order $order)
    {
        // Hiển thị thông tin người đặt và sản phẩm
        return view('order.confirmation', compact('order'));
    }

    public function show(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
    
        // Eager load sản phẩm liên quan
        $order->load('products');
    
        return view('order.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được xoá.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }

    // Phương thức xác nhận đơn hàng
    public function confirmOrder(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
    
        // Đánh dấu đơn hàng là đã được xác nhận
        $order->is_confirmed = true;
        $order->save();
    
        return redirect()->route('order.confirmation', ['order' => $order->id])
                         ->with('success', 'Đơn hàng đã được xác nhận.');
    }
    
}
