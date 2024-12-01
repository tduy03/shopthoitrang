<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thực hiện migration.
     */
    public function up(): void
    {
        // Kiểm tra xem cột production_date đã tồn tại chưa
        if (!Schema::hasColumn('products', 'production_date')) {
            Schema::table('products', function (Blueprint $table) {
                $table->date('production_date')->nullable(); // Thêm cột production_date
            });
        }
    }

    /**
     * Hoàn tác migration.
     */
    public function down(): void
    {
        // Xóa cột production_date nếu nó tồn tại
        if (Schema::hasColumn('products', 'production_date')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('production_date');
            });
        }
    }
};
