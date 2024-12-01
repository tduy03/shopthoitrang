<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Hiển thị danh sách tất cả các sản phẩm
    public function index()
    {
        $products = Product::with('category')->get(); // Lấy tất cả sản phẩm cùng với danh mục
        return view('admin.products.index', compact('products')); // Truyền biến $products vào view
    }

    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục từ cơ sở dữ liệu
        return view('admin.products.create', compact('categories')); // Truyền biến $categories vào view
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'production_date' => 'nullable|date', // Validation cho production_date
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Xử lý upload hình ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
        }

        Product::create(array_merge($request->all(), ['image' => $imagePath]));

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all(); // Lấy tất cả danh mục để hiển thị trong form chỉnh sửa
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'production_date' => 'nullable|date', // Validation cho production_date
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Xử lý upload hình ảnh
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images/products', 'public');
        }

        $product->update(array_merge($request->all(), ['image' => $imagePath]));

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted successfully.');
    }

    // Người dùng bình thường
    public function dinhtuanduyshow(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function dinhtuanduysearch(Request $request)
    {
        $query = $request->input('query');
        
        $products = Product::where('name', 'like', "%$query%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->paginate(10);
    
        return view('welcome', compact('products'));
    }
}
