<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Tạo cột id tự động tăng
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Tạo khóa ngoại cho 'order_id', tự động xóa nếu bản ghi liên quan trong orders bị xóa
            $table->decimal('amount', 10, 2); // Cột số thập phân cho 'amount' với độ dài 10 và 2 chữ số sau dấu thập phân
            $table->timestamp('payment_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Cột thời gian 'payment_date' với giá trị mặc định là thời gian hiện tại
            $table->enum('payment_method', ['COD', 'online'])->default('online'); // Cột enum cho phương thức thanh toán với giá trị mặc định là 'online'
            $table->timestamps(); // Tạo 2 cột 'created_at' và 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments'); // Xóa bảng 'payments' nếu tồn tại
    }
};
