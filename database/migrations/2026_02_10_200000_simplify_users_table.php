<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Add new columns
        Schema::table('users', function (Blueprint $table) {
            // $table->string('first_name')->nullable()->after('name');
            // $table->string('last_name')->nullable()->after('first_name');
            $table->string('avatar_url')->nullable()->after('last_name');
        });

        // Step 2: Migrate data - split name into first_name/last_name
        DB::statement("
            UPDATE users SET
                first_name = SUBSTRING_INDEX(name, ' ', 1),
                last_name = CASE
                    WHEN LOCATE(' ', name) > 0
                    THEN TRIM(SUBSTRING(name, LOCATE(' ', name) + 1))
                    ELSE ''
                END
            WHERE name IS NOT NULL
        ");

        // Step 3: Migrate avatar → avatar_url
        DB::statement("UPDATE users SET avatar_url = avatar WHERE avatar IS NOT NULL");

        // Step 4: Set defaults for null first_name/last_name
        DB::statement("UPDATE users SET first_name = '' WHERE first_name IS NULL");
        DB::statement("UPDATE users SET last_name = '' WHERE last_name IS NULL");

        // Step 5: Make first_name, last_name NOT NULL
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });

        // Step 6: Add gender_new as ENUM and migrate data
        DB::statement("ALTER TABLE users ADD COLUMN gender_new ENUM('male', 'female', 'other', 'unknown') DEFAULT 'unknown' AFTER avatar_url");

        // Migrate gender int values: 0=male, 1=female, 2=other, 3=unknown
        DB::statement("
            UPDATE users SET gender_new = CASE
                WHEN gender = 0 THEN 'male'
                WHEN gender = 1 THEN 'female'
                WHEN gender = 2 THEN 'other'
                ELSE 'unknown'
            END
        ");

        // Step 7: Drop foreign key constraints trước khi drop columns
        Schema::table('users', function (Blueprint $table) {
            $foreignKeys = [
                'affiliate_id'  => 'users_affiliate_id_foreign',
                'plan_id'       => 'users_plan_id_foreign',
                'prefecture_id' => 'users_prefecture_id_foreign',
            ];

            foreach ($foreignKeys as $column => $constraintName) {
                if (Schema::hasColumn('users', $column)) {
                    try {
                        $table->dropForeign($constraintName);
                    } catch (\Exception $e) {
                        // Constraint may not exist, skip
                    }
                }
            }
        });

        // Step 8: Drop all unused columns
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            $allColumnsToDrop = [
                'name', 'user_id', 'avatar', 'avatar_original', 'cover_image', 'cover_photo',
                'discord_name', 'phone_number', 'referral_code',
                'email_notification', 'receive_notification', 'read_privacy_terms',
                'affiliate_id', 'dark_mode', 'last_login_at',
                'is_online', 'last_activity_at', 'last_seen_at',
                'status', 'description', 'image',
                'active', 'change_email', 'change_email_token', 'change_email_token_expire',
                'plan_id', 'sns_limits', 'sns_developer', 'trial',
                'company_name', 'postcode', 'prefecture_id', 'city', 'address',
                'gender', // old integer gender
                'birthday',
                'can_see_post', 'can_see_comment', 'private_mode',
                'last_name_kanji', 'first_name_kanji', 'last_name_kana', 'first_name_kana',
                'line_name', 'group_name', 'group_name_kana',
                'postal_code', 'street_address', 'building', 'phone', 'email_send',
                'account_type', 'account_number', 'customer_id',
                'is_black', 'memo', 'billing_same_address_flag',
                'payment_status', 'last_update_payment', 'admin_id_update_payment',
                'last_change_status', 'verified',
                'is_suspended', 'suspended_at', 'suspension_count',
            ];

            foreach ($allColumnsToDrop as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // Step 9: Rename gender_new → gender
        DB::statement("ALTER TABLE users CHANGE COLUMN gender_new gender ENUM('male', 'female', 'other', 'unknown') DEFAULT 'unknown'");
    }

    public function down(): void
    {
        // Add back essential columns for rollback
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('user_id')->nullable()->after('id');
            $table->string('avatar')->nullable();
        });

        // Restore name from first_name + last_name
        DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name)");

        // Restore avatar from avatar_url
        DB::statement("UPDATE users SET avatar = avatar_url WHERE avatar_url IS NOT NULL");

        // Drop new columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'avatar_url']);
        });

        // Change gender back to tinyInteger
        DB::statement("ALTER TABLE users MODIFY COLUMN gender TINYINT DEFAULT 3");
        DB::statement("
            UPDATE users SET gender = CASE
                WHEN gender = 'male' THEN 0
                WHEN gender = 'female' THEN 1
                WHEN gender = 'other' THEN 2
                ELSE 3
            END
        ");
    }
};
