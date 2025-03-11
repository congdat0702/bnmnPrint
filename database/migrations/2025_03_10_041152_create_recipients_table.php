<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->text('address')->nullable(); // Địa chỉ có thể bỏ trống
            $table->string('company')->nullable(); // Công ty có thể bỏ trống
            $table->decimal('cod', 8, 2)->nullable(); // Giá trị COD
            $table->string('item')->nullable(); // Mặt hàng
            $table->string('payer')->nullable(); // Người thanh toán
            $table->timestamps(); // Các cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipients');
    }
}