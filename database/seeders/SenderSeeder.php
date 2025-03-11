<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sender;

class SenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sender::create([
            'name' => 'CỬA HÀNG BƠM NƯỚC MINI VẠN THẮNG',
            'contact' => 'NGUYỄN ĐỨC THẮNG | 0977666881 - 0902681015', // Thông tin liên hệ
            'address' => '159/8 NGÔ QUYỀN, P6, Q10, TPHCM', // Địa chỉ
        ]);
    }
}