<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Seed prefecture data if not exists
        $prefectures = [
            ['name' => '北海道', 'order_num' => 1],
            ['name' => '青森県', 'order_num' => 2],
            ['name' => '岩手県', 'order_num' => 3],
            ['name' => '宮城県', 'order_num' => 4],
            ['name' => '秋田県', 'order_num' => 5],
            ['name' => '山形県', 'order_num' => 6],
            ['name' => '福島県', 'order_num' => 7],
            ['name' => '茨城県', 'order_num' => 8],
            ['name' => '栃木県', 'order_num' => 9],
            ['name' => '群馬県', 'order_num' => 10],
            ['name' => '埼玉県', 'order_num' => 11],
            ['name' => '千葉県', 'order_num' => 12],
            ['name' => '東京都', 'order_num' => 13],
            ['name' => '神奈川県', 'order_num' => 14],
            ['name' => '新潟県', 'order_num' => 15],
            ['name' => '富山県', 'order_num' => 16],
            ['name' => '石川県', 'order_num' => 17],
            ['name' => '福井県', 'order_num' => 18],
            ['name' => '山梨県', 'order_num' => 19],
            ['name' => '長野県', 'order_num' => 20],
            ['name' => '岐阜県', 'order_num' => 21],
            ['name' => '静岡県', 'order_num' => 22],
            ['name' => '愛知県', 'order_num' => 23],
            ['name' => '三重県', 'order_num' => 24],
            ['name' => '滋賀県', 'order_num' => 25],
            ['name' => '京都府', 'order_num' => 26],
            ['name' => '大阪府', 'order_num' => 27],
            ['name' => '兵庫県', 'order_num' => 28],
            ['name' => '奈良県', 'order_num' => 29],
            ['name' => '和歌山県', 'order_num' => 30],
            ['name' => '鳥取県', 'order_num' => 31],
            ['name' => '島根県', 'order_num' => 32],
            ['name' => '岡山県', 'order_num' => 33],
            ['name' => '広島県', 'order_num' => 34],
            ['name' => '山口県', 'order_num' => 35],
            ['name' => '徳島県', 'order_num' => 36],
            ['name' => '香川県', 'order_num' => 37],
            ['name' => '愛媛県', 'order_num' => 38],
            ['name' => '高知県', 'order_num' => 39],
            ['name' => '福岡県', 'order_num' => 40],
            ['name' => '佐賀県', 'order_num' => 41],
            ['name' => '長崎県', 'order_num' => 42],
            ['name' => '熊本県', 'order_num' => 43],
            ['name' => '大分県', 'order_num' => 44],
            ['name' => '宮崎県', 'order_num' => 45],
            ['name' => '鹿児島県', 'order_num' => 46],
            ['name' => '沖縄県', 'order_num' => 47],
            ['name' => '海外', 'order_num' => 48],
        ];

        $now = now();
        foreach ($prefectures as $pref) {
            $exists = DB::table('prefectures')->where('name', $pref['name'])->exists();
            if (!$exists) {
                DB::table('prefectures')->insert([
                    'prefecture_id' => strtolower((string) Str::ulid()),
                    'name' => $pref['name'],
                    'order_num' => $pref['order_num'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 2. Migrate prefecture string -> prefecture_id (for rows not yet migrated)
        DB::statement('
            UPDATE user_address_jp
            INNER JOIN prefectures ON user_address_jp.prefecture = prefectures.name
            SET user_address_jp.prefecture_id = prefectures.prefecture_id
            WHERE user_address_jp.prefecture IS NOT NULL
              AND user_address_jp.prefecture_id IS NULL
        ');

        // 3. Drop prefecture (string) and city columns
        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->dropColumn(['prefecture', 'city']);
        });
    }

    public function down(): void
    {
        Schema::table('user_address_jp', function (Blueprint $table) {
            $table->string('prefecture', 50)->nullable()->after('prefecture_id')->comment('都道府県');
            $table->string('city', 100)->nullable()->after('prefecture')->comment('市区町村');
        });

        // Restore prefecture string from prefectures
        DB::statement('
            UPDATE user_address_jp
            INNER JOIN prefectures ON user_address_jp.prefecture_id = prefectures.prefecture_id
            SET user_address_jp.prefecture = prefectures.name
        ');
    }
};
