<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_histories', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('printed_by'); // Người in (nếu cần, có thể sử dụng foreign key)
            $table->timestamp('printed_at')->nullable(); // Ngày giờ in
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
        Schema::dropIfExists('print_histories');
    }
}