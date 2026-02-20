<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_address_vn', function (Blueprint $table) {
            $table->foreignId('address_id')->primary()->constrained('user_addresses')->cascadeOnDelete();
            $table->string('province_city', 100)->comment('Tỉnh/Thành phố');
            $table->string('district', 100)->comment('Quận/Huyện/Thị trấn');
            $table->string('ward_commune', 100)->comment('Xã/Phường');
            $table->string('detail_address', 255)->comment('Địa chỉ chi tiết');
            $table->string('building_name', 150)->nullable()->comment('Tên tòa nhà');
            $table->string('room_no', 50)->nullable()->comment('Số phòng');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_address_vn');
    }
};
