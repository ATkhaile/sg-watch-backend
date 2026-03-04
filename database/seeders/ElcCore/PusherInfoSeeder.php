<?php

namespace Database\Seeders\ElcCore;

use App\Enums\PushType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PusherInfoSeeder extends Seeder
{
    public function run(): void
    {
        // Pusher settings
        DB::table('pusher_infos')->updateOrInsert(
            ['push_type' => PushType::PUSHER],
            [
                'pusher_app_id' => '2120644',
                'pusher_app_key' => 'c84c834e526013c43c55',
                'pusher_app_secret' => '5ba2bbf27f60f7a3ca73',
                'pusher_app_cluster' => 'ap3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Firebase settings
        DB::table('pusher_infos')->updateOrInsert(
            ['push_type' => PushType::FIREBASE],
            [
                'firebase_project_id' => 'sgwatch-b8282',
                'firebase_credentials_path' => 'storage/app/sgwatch-b8282-firebase-adminsdk-fbsvc-a5539270bc.json',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
