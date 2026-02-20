<?php

namespace Database\Seeders\ElcCore;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class MembershipActionSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'membership_action_default',
                'value' => 'none', // none, membership_only, full
            ],
            [
                'key' => 'membership_action_skip_confirmation',
                'value' => 'false', // true or false
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
