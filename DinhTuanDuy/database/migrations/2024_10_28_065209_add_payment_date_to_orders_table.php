<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_payment_date_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDateToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('payment_date')->nullable(); // Thêm trường ngày thanh toán
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_date'); // Xóa trường ngày thanh toán nếu cần
        });
    }
}
