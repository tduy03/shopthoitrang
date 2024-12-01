<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order; // Chú ý tên lớp Order

class Payment extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán hàng loạt.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'amount', 'payment_date', 'payment_method'];

    /**
     * Định nghĩa quan hệ giữa Payment và Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class); // Định nghĩa quan hệ belongsTo
    }
}
