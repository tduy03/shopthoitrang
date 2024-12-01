<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Nhớ import Carbon để xử lý ngày tháng

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'payment_method',
        'total_amount',
        'discount_amount', // Thêm cột tổng giảm giá vào đây
        'discount_code', // Thêm cột mã giảm giá vào đây
        'payment_date', // Thêm cột ngày thanh toán vào đây
        'status', // Thêm cột status vào đây
        'is_confirmed', // Thêm thuộc tính này
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }

    // Hàm để áp dụng mã giảm giá
    public function applyDiscount()
    {
        if ($this->payment_date) {
            $paymentDate = Carbon::parse($this->payment_date);
            if ($paymentDate->isSaturday() || $paymentDate->isSunday()) {
                $this->discount_code = 'WEEKEND10';
                $this->discount_amount = $this->total_amount * 0.10; // Giảm giá 10%
                $this->total_amount -= $this->discount_amount; // Cập nhật tổng số tiền
            }
        }
    }
}
