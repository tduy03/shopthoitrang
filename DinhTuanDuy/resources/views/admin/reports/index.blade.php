@extends('layouts.app') 
@section('title', 'Báo cáo') 
@section('content') 
<div class="container">
    <h1>Báo cáo</h1>

    <form method="GET" action="{{ route('admin.reports.index') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="start_date">Ngày bắt đầu:</label>
                <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Ngày kết thúc:</label>
                <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
        </div>
    </form>
    <div class="mt-4">
        <h4>Tổng số đơn hàng: {{ $totalOrders }}</h4>
        <h4>Tổng số khách hàng: {{ $totalCustomers }}</h4>
    </div>

    <h3>Doanh thu theo danh mục</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID Danh mục</th>
                <th>Tên danh mục</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryRevenue as $revenue)
            <tr>
                <td>{{ $revenue->category_id }}</td>
                <td>{{ $revenue->category_name }}</td>
                <td>{{ number_format($revenue->total_revenue, 2) }} VND</td>
            </tr> 
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo ngày</h3>
    <canvas id="revenueByDateChart" width="400" height="200"></canvas>
    <table class="table">
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByDate as $revenue)
            <tr>
                <td>{{ $revenue->date }}</td>
                <td>{{ number_format($revenue->total_revenue, 2) }} VND</td>
            </tr> 
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo tháng</h3>
    <canvas id="revenueByMonthChart" width="400" height="200"></canvas>
    <table class="table">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByMonth as $revenue)
            <tr>
                <td>{{ $revenue->month }}</td>
                <td>{{ number_format($revenue->total_revenue, 2) }} VND</td>
            </tr> 
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo năm</h3>
    <canvas id="revenueByYearChart" width="400" height="200"></canvas>
    <table class="table">
        <thead>
            <tr>
                <th>Năm</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByYear as $revenue)
            <tr>
                <td>{{ $revenue->year }}</td>
                <td>{{ number_format($revenue->total_revenue, 2) }} VND</td>
            </tr> 
            @endforeach
        </tbody>
    </table>

    <h3>Doanh thu theo phương thức thanh toán</h3>
    <canvas id="revenueByPaymentMethodChart" width="400" height="200"></canvas>
    <table class="table">
        <thead>
            <tr>
                <th>Phương thức thanh toán</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByPaymentMethod as $revenue)
            <tr>
                <td>{{ $revenue->payment_method }}</td>
                <td>{{ number_format($revenue->total_revenue, 2) }} VND</td>
            </tr> 
            @endforeach
        </tbody>
    </table>
</div> 

<!-- Thêm Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Biểu đồ doanh thu theo ngày
    const revenueByDateLabels = @json($revenueByDate->pluck('date'));
    const revenueByDateData = @json($revenueByDate->pluck('total_revenue'));

    const ctxDate = document.getElementById('revenueByDateChart').getContext('2d');
    const revenueByDateChart = new Chart(ctxDate, {
        type: 'line',
        data: {
            labels: revenueByDateLabels,
            datasets: [{
                label: 'Doanh thu theo ngày',
                data: revenueByDateData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ doanh thu theo tháng
    const revenueByMonthLabels = @json($revenueByMonth->pluck('month'));
    const revenueByMonthData = @json($revenueByMonth->pluck('total_revenue'));

    const ctxMonth = document.getElementById('revenueByMonthChart').getContext('2d');
    const revenueByMonthChart = new Chart(ctxMonth, {
        type: 'bar',
        data: {
            labels: revenueByMonthLabels,
            datasets: [{
                label: 'Doanh thu theo tháng',
                data: revenueByMonthData,
                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ doanh thu theo năm
    const revenueByYearLabels = @json($revenueByYear->pluck('year'));
    const revenueByYearData = @json($revenueByYear->pluck('total_revenue'));

    const ctxYear = document.getElementById('revenueByYearChart').getContext('2d');
    const revenueByYearChart = new Chart(ctxYear, {
        type: 'bar',
        data: {
            labels: revenueByYearLabels,
            datasets: [{
                label: 'Doanh thu theo năm',
                data: revenueByYearData,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ doanh thu theo phương thức thanh toán
    const paymentMethodLabels = @json($revenueByPaymentMethod->pluck('payment_method'));
    const paymentMethodData = @json($revenueByPaymentMethod->pluck('total_revenue'));

    const ctxPaymentMethod = document.getElementById('revenueByPaymentMethodChart').getContext('2d');
    const revenueByPaymentMethodChart = new Chart(ctxPaymentMethod, {
        type: 'pie',
        data: {
            labels: paymentMethodLabels,
            datasets: [{
                label: 'Doanh thu theo phương thức thanh toán',
                data: paymentMethodData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Doanh thu theo phương thức thanh toán'
                }
            }
        }
    });
</script>

@endsection
