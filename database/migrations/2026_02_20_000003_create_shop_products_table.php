<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('name')->comment('Tên sản phẩm');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->comment('Mã sản phẩm / Model');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable()->comment('Mô tả chi tiết');
            $table->text('product_info')->nullable()->comment('Thông tin sản phẩm');
            $table->text('deal_info')->nullable()->comment('Thông tin khuyến mãi');

            // Giá
            $table->decimal('price_jpy', 12, 0)->default(0)->comment('Giá JPY');
            $table->decimal('price_vnd', 15, 0)->default(0)->comment('Giá VND');
            $table->decimal('original_price_jpy', 12, 0)->nullable()->comment('Giá gốc JPY');
            $table->decimal('original_price_vnd', 15, 0)->nullable()->comment('Giá gốc VND');
            $table->integer('points')->default(0)->comment('Điểm tích lũy');

            // Thuộc tính chung
            $table->string('gender')->nullable()->comment('Giới tính: male, female, unisex');
            $table->string('movement_type')->nullable()->comment('Loại máy: quartz (pin), automatic (cơ), manual, solar, kinetic');
            $table->string('condition')->default('new')->comment('Tình trạng: new, display, used');

            // Thuộc tính riêng theo loại sản phẩm (linh hoạt mở rộng)
            $table->json('attributes')->nullable()->comment('Thuộc tính động theo loại SP (case_size, CPU, RAM...)');

            // Kho & trạng thái
            $table->integer('stock_quantity')->default(0);
            $table->integer('warranty_months')->default(0)->comment('Bảo hành (tháng)');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false)->comment('Sản phẩm nổi bật');
            $table->integer('sort_order')->default(0);

            // Thống kê
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('sold_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('shop_categories')->nullOnDelete();
            $table->foreign('brand_id')->references('id')->on('shop_brands')->nullOnDelete();

            $table->index(['is_active', 'sort_order']);
            $table->index('gender');
            $table->index('movement_type');
            $table->index('condition');
            $table->index('price_jpy');
            $table->index('price_vnd');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};
