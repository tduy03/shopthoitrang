<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Lấy ngày bắt đầu và kết thúc từ request, nếu không có giá trị thì mặc định là null
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Doanh thu theo phương thức thanh toán
        $revenueByPaymentMethod = DB::table('orders')
            ->select('payment_method', DB::raw('SUM(total_amount) as total_revenue'))
            ->where('status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('payment_method')
            ->get();

        // Thống kê tổng doanh thu theo từng danh mục
        $categoryRevenue = DB::table('order_items')
            ->select('categories.id as category_id', 'categories.name as category_name', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('orders.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('orders.created_at', '<=', $endDate);
            })
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Tổng số đơn hàng
        $totalOrders = Order::where('status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();

        // Tổng số khách hàng
        $totalCustomers = DB::table('users')->where('role', 'user')->count();

        // Doanh thu theo ngày
        $revenueByDate = Order::select(DB::raw('DATE(created_at) as date, SUM(total_amount) as total_revenue'))
            ->where('status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('date')
            ->get();

        // Doanh thu theo tháng
        $revenueByMonth = Order::select(DB::raw('MONTH(created_at) as month, SUM(total_amount) as total_revenue'))
            ->where('status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('month')
            ->get();

        // Doanh thu theo năm
        $revenueByYear = Order::select(DB::raw('YEAR(created_at) as year, SUM(total_amount) as total_revenue'))
            ->where('status', 'shipped')
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('year')
            ->get();

        // Trả về view với dữ liệu cần thiết
        return view('admin.reports.index', compact('categoryRevenue', 'totalOrders', 'totalCustomers', 'revenueByDate', 'revenueByMonth', 'revenueByYear', 'revenueByPaymentMethod', 'startDate', 'endDate'));
    }
}
