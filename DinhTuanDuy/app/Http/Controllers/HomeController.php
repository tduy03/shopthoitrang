<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('home', compact('categories')); // Truyền biến $categories vào view
    }

    public function showCategoryProducts(Category $category)
    {
        $products = $category->products; // Lấy sản phẩm thuộc danh mục
        return view('category_products', compact('category', 'products'));
    }
}

