<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên người dùng
            $table->string('email')->unique(); // Email phải là duy nhất
            $table->string('password'); // Mật khẩu
            $table->enum('role', ['user', 'admin'])->default('user'); // Quyền người dùng
            $table->timestamp('email_verified_at')->nullable(); // Thời gian xác thực email
            $table->rememberToken(); // Token cho tính năng "Nhớ tôi"
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
        Schema::dropIfExists('users'); // Xóa bảng users khi rollback
    }
}