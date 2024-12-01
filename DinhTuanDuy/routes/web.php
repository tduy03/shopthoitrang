<?php

use Illuminate\Support\Facades\Route;
// Import controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ReportController;

// Route gửi OTP
Route::post('/order/send-otp', [OrderController::class, 'sendOtp'])->name('order.sendOtp');

// Route xác thực OTP
Route::post('/order/verify-otp', [OrderController::class, 'verifyOtp'])->name('order.verifyOtp');

Route::get('/orders/{order}/confirm', [OrderController::class, 'confirmOrder'])->name('orders.confirm');

// Route quân ly bào cào
Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');

Route::get('/search', [ProductController::class, 'dinhtuanduysearch'])->name('products.search');



Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
Route::get('orders/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');


//route đặt hàng
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::middleware(['auth'])->post('/order/place', [CartController::class, 'placeOrder'])->name('order.place');

Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// routes/web.php
Route::middleware(['auth'])->get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::middleware(['auth'])->get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::middleware(['auth'])->delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Route để thêm sản phẩm vào giỏ hàng
Route::middleware(['auth'])->post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
// Route để hiển thị giỏ hàng
Route::middleware(['auth'])->get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route để xoá sản phẩm khỏi giỏ hàng
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');


Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');

// Trang chủ welcome
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Đăng ký và đăng nhập người dùng
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route dành cho admin (sử dụng middleware để kiểm tra quyền)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Route quản lý sản phẩm
    Route::resource('/products', ProductController::class);

    // Route quản lý danh mục
    Route::resource('/categories', CategoryController::class);
});

// Route cho người dùng bình thường
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [WelcomeController::class, 'index'])->name('home'); // Đã thêm route home
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'dinhtuanduyshow'])->name('products.show');
});
