<?php

namespace App\Components;

use App\Enums\PointMasterStatus;
use App\Models\User;
use App\Models\PointHistory;
use App\Models\UserPointSetting;
use App\Models\PointMaster;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Enums\PointMasterType;

class PointBonusComponent
{
    public static function dailyBonus(): void
    {
        $user = Auth::guard('api')->user();
        $flagPlusPoint = false;

        if (env('TEST_DAILY_BONUS')) {
            if (!PointHistory::where([
                ['user_id', $user->id],
                ['point_type', 'daily_bonus'],
                ['created_at', '>=', Carbon::now()->subMinutes(10)]
            ])->exists()) {
                $flagPlusPoint = true;
            }
        } else {
            // Check if user already got daily bonus today
            $lastDailyBonus = PointHistory::where('user_id', $user->id)
                ->where('point_type', PointMasterType::DAILY_BONUS)
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$lastDailyBonus) {
                $flagPlusPoint = true;
            }
        }
        if (!$flagPlusPoint) {
            return;
        }

        $pointSetting = self::getPointSetting($user->id);

        if (!$pointSetting) {
            return;
        }

        $pointHistory = new PointHistory();
        $pointHistory->user_id = $user->id;
        $pointHistory->point = $pointSetting['point'];
        $pointHistory->memo = 'Daily bonus';
        $pointHistory->point_type = PointMasterType::DAILY_BONUS;
        $pointHistory->last_update_user_id = $user->id;
        $pointHistory->save();
    }

    private static function getPointSetting(int $userId): ?array
    {
        $now = Carbon::now();
        $userPointSetting = UserPointSetting::query()
            ->where('user_id', $userId)
            ->where('type', PointMasterType::DAILY_BONUS)
            ->where('status', PointMasterStatus::ON)
            ->where('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            })
            ->first();

        if ($userPointSetting) {
            return [
                'point' => $userPointSetting->point,
                'source' => 'user_setting'
            ];
        }

        $pointMaster = PointMaster::query()
            ->where('type', PointMasterType::DAILY_BONUS)
            ->where('status', PointMasterStatus::ON)
            ->where('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            })
            ->first();

        if ($pointMaster) {
            return [
                'point' => $pointMaster->point,
                'source' => 'master_setting'
            ];
        }

        return null;
    }

    public static function affiliateBonus(int $inviterId, int $inviteeId): void
    {
        foreach ([
            ['user_id' => $inviterId, 'role' => 'inviter'],
            ['user_id' => $inviteeId, 'role' => 'invitee'],
        ] as $data) {
            $setting = self::getAffiliatePointSetting($data['user_id'], $data['role']);

            if (!$setting) {
                continue;
            }

            PointHistory::create([
                'user_id'             => $data['user_id'],
                'point'               => $setting['point'],
                'memo'                => 'Affiliate bonus',
                'point_type'          => PointMasterType::AFFILIATE_BONUS,
                'last_update_user_id' => $data['user_id'],
            ]);
        }
    }

    private static function getAffiliatePointSetting(int $userId, string $role): ?array
    {
        $now = Carbon::now();

        $userPointSetting = UserPointSetting::query()
            ->where('user_id', $userId)
            ->where('type', PointMasterType::AFFILIATE_BONUS)
            ->where('status', PointMasterStatus::ON)
            ->where('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            })
            ->first();

        if ($userPointSetting) {
            return [
                'point' => $userPointSetting->point,
                'source' => 'user_setting'
            ];
        }

        $pointMaster = PointMaster::query()
            ->where('type', PointMasterType::AFFILIATE_BONUS)
            ->where('status', PointMasterStatus::ON)
            ->where('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $now);
            })
            ->first();

        if ($pointMaster) {
            $pointColumn = $role === 'inviter' ? 'inviter_point' : 'invitee_point';
            return [
                'point' => $pointMaster->$pointColumn,
                'source' => 'master_setting'
            ];
        }

        return null;
    }
}
