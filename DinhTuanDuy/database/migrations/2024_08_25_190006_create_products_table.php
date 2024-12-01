<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra xem bảng đã tồn tại chưa
        if (Schema::hasTable('products')) {
            // Kiểm tra xem cột quantity đã tồn tại chưa
            if (!Schema::hasColumn('products', 'quantity')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->integer('quantity')->default(0);
                });
            }
        } else {
            // Tạo bảng nếu nó không tồn tại
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('quantity')->default(0); // Thêm cột quantity
                $table->foreignId('category_id')->nullable()->constrained('categories');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

