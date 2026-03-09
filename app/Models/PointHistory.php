<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointHistory extends Model
{
    use SoftDeletes;

    protected $table = 'point_histories';

    protected $fillable = [
        'user_id',
        'point',
        'memo',
        'point_type',
        'show_popup_flag',
        'last_update_user_id',
        'expired_at',
        'remaining_point',
    ];

    protected $casts = [
        'point' => 'integer',
        'remaining_point' => 'integer',
        'show_popup_flag' => 'boolean',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastUpdateUser()
    {
        return $this->belongsTo(User::class, 'last_update_user_id');
    }

    /**
     * Tính tổng điểm khả dụng (chưa hết hạn, còn remaining) của user.
     */
    public static function getAvailablePoints(int $userId): int
    {
        return (int) self::where('user_id', $userId)
            ->where('remaining_point', '>', 0)
            ->where('expired_at', '>', Carbon::now())
            ->sum('remaining_point');
    }

    /**
     * Trừ điểm theo FIFO (dùng điểm cũ nhất trước).
     * Trả về số điểm thực tế đã trừ.
     */
    public static function spendPoints(int $userId, int $amount): int
    {
        if ($amount <= 0) {
            return 0;
        }

        $records = self::where('user_id', $userId)
            ->where('remaining_point', '>', 0)
            ->where('expired_at', '>', Carbon::now())
            ->orderBy('expired_at', 'asc')
            ->get();

        $remaining = $amount;

        foreach ($records as $record) {
            if ($remaining <= 0) {
                break;
            }

            $deduct = min($remaining, $record->remaining_point);
            $record->decrement('remaining_point', $deduct);
            $remaining -= $deduct;
        }

        return $amount - $remaining;
    }

    /**
     * Đồng bộ user.point = tổng điểm khả dụng từ point_histories.
     */
    public static function syncUserPoint(int $userId): void
    {
        $available = self::getAvailablePoints($userId);
        User::where('id', $userId)->update(['point' => $available]);
    }
}
