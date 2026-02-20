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
        Schema::table('plans', function (Blueprint $table) {
            // Drop unused columns
            $table->dropColumn([
                'price',
                'plan_type',
                'sns_limits',
                'sns_developer',
                'stripe_plan_id',
                'stripe_key',
                'stripe_secret',
                'stripe_payment_link',
                'stripe_webhook_secret',
                'cancel_hours',
            ]);

            // Add new columns
            $table->string('icon_url')->nullable()->after('name');
            $table->string('plan_text')->nullable()->after('icon_url');
            $table->text('description')->nullable()->after('plan_text');
            $table->unsignedBigInteger('payment_trigger_id')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('payment_trigger_id');
            $table->boolean('for_guest')->default(false)->after('is_active');
            $table->unsignedBigInteger('category_id')->nullable()->after('for_guest');
            $table->json('tags')->nullable()->after('category_id');
            $table->integer('display_order')->default(0)->after('tags');

            // Foreign keys
            $table->foreign('payment_trigger_id')
                ->references('id')
                ->on('payment_triggers')
                ->onDelete('set null');

            $table->foreign('category_id')
                ->references('id')
                ->on('category')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['payment_trigger_id']);
            $table->dropForeign(['category_id']);

            // Drop new columns
            $table->dropColumn([
                'icon_url',
                'plan_text',
                'description',
                'payment_trigger_id',
                'is_active',
                'for_guest',
                'category_id',
                'tags',
                'display_order',
            ]);

            // Restore old columns
            $table->integer('price')->after('name');
            $table->tinyInteger('plan_type')->after('price');
            $table->longText('sns_limits')->nullable()->after('plan_type');
            $table->longText('sns_developer')->nullable()->after('sns_limits');
            $table->string('stripe_plan_id')->after('sns_developer');
            $table->text('stripe_key')->after('stripe_plan_id');
            $table->text('stripe_secret')->after('stripe_key');
            $table->text('stripe_payment_link')->after('stripe_secret');
            $table->string('stripe_webhook_secret')->after('stripe_payment_link');
            $table->double('cancel_hours')->nullable()->default(120)->after('stripe_webhook_secret');
        });
    }
};
