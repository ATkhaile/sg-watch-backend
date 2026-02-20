<?php

use App\Enums\Aiship\AccessLimit;
use App\Enums\Aiship\PublishStatus;
use App\Enums\Aiship\ShippingType;
use App\Enums\Aiship\SoldoutSetting;
use App\Enums\Aiship\StockDisplaySetting;
use App\Enums\Aiship\StockType;
use App\Enums\Aiship\TaxCategory;
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
        Schema::create('aiship_product_id', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 50)->unique();                     
            $table->string('product_name', 255);                              
            $table->enum('publish_status', PublishStatus::getValues());     

            $table->string('control_flag', 50)->nullable();                   
            $table->dateTime('publish_start_date')->nullable();               
            $table->dateTime('publish_end_date')->nullable();              
            $table->text('short_description_mobile')->nullable();            
            $table->text('short_description')->nullable();                    
            $table->enum('stock_type', StockType::getValues())->nullable();   
            $table->integer('stock_quantity')->nullable();                   
            $table->decimal('sale_price', 10, 2)->nullable();               
            $table->string('product_code', 50)->nullable();                  
            $table->text('detail_description_mobile')->nullable();           
            $table->text('detail_description')->nullable();                  
            $table->decimal('list_price', 10, 2)->nullable();                 
            $table->enum('stock_display_setting', StockDisplaySetting::getValues())->nullable();
            $table->integer('low_stock_threshold')->nullable();               
            $table->enum('soldout_setting', SoldoutSetting::getValues())->nullable();    
            $table->enum('shipping_type', ShippingType::getValues())->nullable();
            $table->string('delivery_type', 100)->nullable();              
            $table->decimal('individual_shipping_fee', 10, 2)->nullable();   
            $table->integer('min_purchase_limit')->nullable();               
            $table->integer('max_purchase_limit')->nullable();              
            $table->boolean('pr_setting')->nullable();                       
            $table->boolean('review_setting')->nullable();                 
            $table->boolean('bulk_purchase_setting')->nullable();             
            $table->boolean('sale_period_enable')->nullable();                
            $table->dateTime('sale_start_datetime')->nullable();             
            $table->dateTime('sale_end_datetime')->nullable();                
            $table->boolean('sale_period_display')->nullable();               
            $table->enum('access_limit', AccessLimit::getValues())->nullable();
            $table->integer('display_priority')->nullable();                 
            $table->decimal('weight', 8, 2)->nullable();                      
            $table->string('thumbnail_url', 255)->nullable();               
            $table->string('image_url', 255)->nullable();                     
            $table->string('image_description', 255)->nullable();             
            $table->string('page_title', 255)->nullable();                   
            $table->string('keywords', 255)->nullable();                      
            $table->text('description')->nullable();                         
            $table->text('search_keyword_setting')->nullable();              
            $table->string('layout_type', 100)->nullable();                   
            $table->text('free_area_order')->nullable();                     
            $table->integer('purchase_limit_count')->nullable();             
            $table->text('head_insert')->nullable();                         
            $table->boolean('subscription_setting')->nullable();             
            $table->integer('mail_bin_limit')->nullable();                   
            $table->boolean('hide_from_list')->nullable();                   
            $table->boolean('trial_product_flag')->nullable();               
            $table->string('payment_disabled', 255)->nullable();              
            $table->boolean('shipping_disabled')->nullable();                 
            $table->date('release_date')->nullable();                         
            $table->boolean('release_date_display')->nullable();             
            $table->string('template_selection', 100)->nullable();            
            $table->boolean('variation_price_enable')->nullable();            
            $table->text('related_products')->nullable();                     
            $table->enum('tax_category', TaxCategory::getValues())->nullable();  
            $table->dateTime('created_at_external')->nullable();             
            $table->string('image_title', 255)->nullable();                   
            $table->boolean('google_feed_output')->nullable();                
            $table->boolean('facebook_feed_output')->nullable();              
            $table->string('google_product_category', 255)->nullable();      
            $table->string('jan_code', 13)->nullable();                       
            $table->string('color', 100)->nullable();                         
            $table->string('brand', 100)->nullable();                         
            $table->string('size', 50)->nullable();                           
            $table->text('product_option')->nullable();                       
            $table->text('common_option')->nullable();                       
            $table->text('product_sort_setting')->nullable();                 
            $table->boolean('social_gift_setting')->nullable();              
            $table->string('delivery_pattern_setting', 100)->nullable();      
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aiship_product_id');
    }
};
