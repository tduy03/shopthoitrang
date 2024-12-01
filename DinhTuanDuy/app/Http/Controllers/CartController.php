<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        
        // Lấy giỏ hàng từ cơ sở dữ liệu cho người dùng hiện tại
        $cartItems = Cart::where('user_id', Auth::id())->with('product.category')->get(); // Thêm eager loading để lấy sản phẩm và danh mục
         // Nếu giỏ hàng trống
        if ($cartItems->isEmpty()) {
            return view('cart.index', ['cart' => [], 'total' => 0]);
        }

        // Lưu giỏ hàng vào session và tạo mảng cart để đưa ra view
        $cart = $cartItems->map(function ($item) {
            return [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        });

        // Tính tổng tiền
        $total = $cart->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.index', ['cart' => $cart, 'total' => $total]);
    }

// Thêm sản phẩm vào giỏ hàng
public function add(Request $request, Product $product)
{
    // Kiểm tra nếu sản phẩm đã hết hàng
    if ($product->quantity <= 0) {
        return redirect()->route('cart.index')->with('error', 'Sản phẩm này đã hết hàng và không thể thêm vào giỏ hàng.');
    }

    // Thêm hoặc cập nhật sản phẩm trong cơ sở dữ liệu
    $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

    if ($cart) {
        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        $cart->quantity++;
        $cart->save();
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
    }

    // Cập nhật session
    $cartSession = session()->get('cart', []);
    if (isset($cartSession[$product->id])) {
        $cartSession[$product->id]['quantity']++;
    } else {
        $cartSession[$product->id] = [
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'category' => $product->category->name,
        ];
    }
    session()->put('cart', $cartSession);

    return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
}


    // Cập nhật số lượng sản phẩm trong giỏ hàng
public function update(Request $request, Product $product)
{
    // Lấy giỏ hàng của người dùng hiện tại
    $cart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

    if ($cart) {
        // Lấy số lượng sản phẩm có sẵn từ cơ sở dữ liệu
        $availableQuantity = $product->quantity;

        if ($request->action === 'increase') {
            // Tăng số lượng nếu không vượt quá số lượng có sẵn trong kho
            if ($cart->quantity < $availableQuantity) {
                $cart->quantity++;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm không đủ.'
                ]);
            }
        } elseif ($request->action === 'decrease' && $cart->quantity > 1) {
            // Giảm số lượng nếu số lượng trong giỏ hàng lớn hơn 1
            $cart->quantity--;
        }

        // Lưu lại số lượng cập nhật
        $cart->save();

        // Tính toán lại tổng tiền của sản phẩm và tổng tiền của giỏ hàng
        $totalPrice = $cart->quantity * $cart->price;
        $cartTotal = Cart::where('user_id', Auth::id())->sum(DB::raw('quantity * price'));

        return response()->json([
            'success' => true,
            'quantity' => $cart->quantity,
            'total' => number_format($totalPrice, 2),
            'cartTotal' => number_format($cartTotal, 2)
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.']);
}


    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Product $product)
    {
        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();
    
        if ($cart) {
            $cart->delete();
        }
    
        // Cập nhật session giỏ hàng
        $cartSession = session()->get('cart', []);
        if (isset($cartSession[$product->id])) {
            unset($cartSession[$product->id]);
            session()->put('cart', $cartSession);
        }
    
        // Tính toán lại tổng tiền của giỏ hàng
        $cartTotal = Cart::where('user_id', Auth::id())->sum(DB::raw('quantity * price'));
    
        // Trả về phản hồi JSON
        return response()->json([
            'success' => true,
            'cartTotal' => number_format($cartTotal, 2),
            'message' => 'Sản phẩm đã được xoá khỏi giỏ hàng.'
        ]);
    }
    
    

   // Đặt hàng
public function placeOrder(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'payment_method' => 'required|string',
        'payment_date' => 'required|date', // Ngày thanh toán
        'discount_code' => 'nullable|string|max:20', // Thêm trường mã giảm giá
    ]);

    DB::beginTransaction();

    try {
        // Lấy giỏ hàng của người dùng hiện tại
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        // Tính tổng số tiền
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Kiểm tra ngày thanh toán
        $paymentDate = Carbon::parse($request->payment_date);
        $discountAmount = 0;

        // Kiểm tra nếu ngày thanh toán là thứ Bảy hoặc Chủ Nhật
        if ($paymentDate->isSaturday() || $paymentDate->isSunday()) {
        }

        // Kiểm tra mã giảm giá từ người dùng, chỉ áp dụng nếu ngày thanh toán là thứ Bảy hoặc Chủ Nhật
        if ($request->discount_code && ($paymentDate->isSaturday() || $paymentDate->isSunday())) {
            // Giả sử bạn có một bảng giảm giá, bạn có thể kiểm tra mã ở đây
            if ($request->discount_code === 'HAVE11') {
                $discountAmount += $totalAmount * 0.10; // Giảm giá thêm 10% nếu mã hợp lệ
            }
        }

        $totalAmount -= $discountAmount; // Cập nhật tổng số tiền

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount, // Thêm thông tin giảm giá
            'discount_code' => $request->discount_code, // Gán mã giảm giá
            'payment_date' => $request->payment_date,
            'is_confirmed' => false, // Đặt mặc định là chưa xác nhận
        ]);

        // Lưu thông tin sản phẩm vào bảng order_items
        foreach ($cartItems as $item) {
            DB::table('order_items')->insert([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Cập nhật số lượng sản phẩm trong kho
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }

        // Gửi email xác nhận chỉ một lần với toàn bộ thông tin đơn hàng
        Mail::to($order->email)->send(new OrderConfirmationMail($order));

        // Xóa giỏ hàng của người dùng
        Cart::where('user_id', Auth::id())->delete();

        DB::commit();

        return redirect()->route('order.confirmation', $order->id)->with('success', 'Đặt hàng thành công.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('cart.index')->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
    }
}

public function confirmation(Order $order)
{
    $order->load('items.product'); // Eager load thông tin sản phẩm

    return view('order.confirmation', compact('order'));
}


}
