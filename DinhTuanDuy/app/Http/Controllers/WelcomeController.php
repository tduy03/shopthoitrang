<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WelcomeController extends Controller
{
    // Phương thức để hiển thị trang chủ với danh sách sản phẩm
    public function index()
    {
        // Lấy tất cả sản phẩm từ database và phân trang
        $products = Product::with('category')->paginate(10);

        // Trả về view 'welcome' với dữ liệu sản phẩm
        return view('welcome', compact('products'));
    }
}
