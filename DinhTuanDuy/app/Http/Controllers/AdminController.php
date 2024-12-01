<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
class AdminController extends Controller
{
    public function dashboard()
    {
    return view('admin.dashboard');
    }
    public function products()
    {
    return app(ProductController::class)->index();
    }
    public function categories()
    {
    return app(CategoryController::class)->index();
    }
    }