<?php

namespace App\Components;

use App\Models\Reservation;
use App\Enums\DetectSqlInjection;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Enums\ReservationStatus;
use Carbon\Carbon;
use App\Models\AvailabilitySlot;
use App\Models\Location;
use App\Models\Holiday;
use App\Models\SlotOption;
use App\Enums\SaunaScheduleStatus;
use App\Models\PaymentPlan;

class CommonComponent
{
    public static function uploadFile($folder, $file, $fileName)
    {
        try {
            // $storage = Storage::disk('public');

            // return $storage->put($folder, $file, $fileName);
            $storage = Storage::disk('public');

            return $storage->putFileAs($folder, $file, $fileName);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function checkFileExists($folder, $fileName)
    {
        try {
            // $storage = Storage::disk('public');

            // return $storage->put($folder, $file, $fileName);
            $storage = Storage::disk('public');

            return $storage->exists($folder . '/' . $fileName);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function uploadFileName($extension = '')
    {
        return sha1(time() . rand(0, 9999999)) . '.' . $extension;
    }

    public static function deleteFile($folder, $nameFile)
    {
        try {
            return Storage::disk('public')->delete($folder . '/' . $nameFile);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function moveFile($oldPath, $newPath, $fileName)
    {
        try {

            $storage = Storage::disk('public');

            return $storage->move($oldPath . '/' . $fileName, $newPath . '/' . $fileName);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public static function getFullUrl($blob)
    {
        // Already a full URL - return as is
        if (str_starts_with($blob, 'http://') || str_starts_with($blob, 'https://')) {
            return $blob;
        }
        return Storage::disk('public')->url($blob);
        $_signature = self::getSASForBlob(env('AZURE_STORAGE_NAME'), env('AZURE_STORAGE_CONTAINER'), $blob, 'b', 'r', env('AZURE_STORAGE_KEY'));
        $_blobUrl = self::getBlobUrl(env('AZURE_STORAGE_NAME'), env('AZURE_STORAGE_CONTAINER'), $blob, 'b', 'r', $_signature);

        return $_blobUrl;
    }

    public static function getSASForBlob($accountName, $container, $blob, $resourceType, $permissions, $key)
    {
        $_arraysign = [];
        $_arraysign[] = $permissions;
        $_arraysign[] = '';
        $_arraysign[] = gmdate("Y-m-d\TH:i:s\Z", strtotime('+ 24 hour'));
        $_arraysign[] = '/' . $accountName . '/' . $container . '/' . $blob;
        $_arraysign[] = '';
        $_arraysign[] = '2014-02-14'; //the API version is now required
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';
        $_arraysign[] = '';

        $_str2sign = implode("\n", $_arraysign);

        return base64_encode(
            hash_hmac('sha256', urldecode(utf8_encode($_str2sign)), base64_decode($key), true)
        );
    }

    public static function getBlobUrl($accountName, $container, $blob, $resourceType, $permissions, $_signature)
    {
        $_parts = [];
        $expiry = gmdate("Y-m-d\TH:i:s\Z", strtotime('+ 24 hour'));
        $_parts[] = (! empty($expiry)) ? 'se=' . urlencode($expiry) : '';
        $_parts[] = 'sr=' . $resourceType;
        $_parts[] = (! empty($permissions)) ? 'sp=' . $permissions : '';
        $_parts[] = 'sig=' . urlencode($_signature);
        $_parts[] = 'sv=2014-02-14';

        /* Create the signed blob URL */
        $accountName = 'https://' . $accountName . '.blob.core.windows.net/';
        $finalUrlPath = (env('ASSET_CDN_URL') !== null) ? env('ASSET_CDN_URL') : $accountName;

        $_url = $finalUrlPath
            . $container . '/'
            . $blob . '?'
            . implode('&', $_parts);

        return $_url;
    }

    public static function urlencode($string)
    {
        $encoded = rawurlencode($string);
        $encoded = str_replace('%7E', '~', $encoded);

        return $encoded;
    }

    public static function newListLimit($query)
    {
        $newSizeLimit = isset($query['limit']) ? $query['limit'] : 9;
        $arrPageSize = [20, 50, 100];
        if (isset($query['limit_page'])) {
            $newSizeLimit = (($query['limit_page'] === '') || ! in_array($query['limit_page'], $arrPageSize)) ? $newSizeLimit : $query['limit_page'];
        }
        if (((isset($query['limit_page']))) && (! empty($query->query('limit_page')))) {
            $newSizeLimit = (! in_array($query->query('limit_page'), $arrPageSize)) ? $newSizeLimit : $query->query('limit_page');
        }

        return $newSizeLimit;
    }

    public static function checkPaginatorList($query)
    {
        if ($query->currentPage() > $query->lastPage()) {
            return true;
        }

        return false;
    }

    public static function getPaginationData(LengthAwarePaginator $data): array
    {
        return [
            'total' => $data->total(),
            'count' => $data->count(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
        ];
    }

    /**
     * [escapeLikeSentence description]
     * @param  [type]  $str    :search conditions
     * @param  bool $before : add % before
     * @param  bool $after  : add % after
     * @return [type]          [description]
     */
    public static function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', self::mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return [[$column, 'LIKE', (($before) ? '%' : '') . $result . (($after) ? '%' : '')]];
    }

    public static function handleSearchQuery($str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', self::mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return (($before) ? '%' : '') . $result . (($after) ? '%' : '');
    }

    public static function mbTrim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);

        return $ret;
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function getVimeoThumbnail($url)
    {
        $curl = curl_init();
        $requestUrl = "https://vimeo.com/api/oembed.json?url=" . urlencode($url) . "&width=480&height=360";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $requestUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    public static function sanitizeSqlInjection($text, $replaceString = ''): string
    {
        if ($text === '') {
            return $text;
        }

        $sqlInjectionPatterns = DetectSqlInjection::getValues();

        $cleaned = str_ireplace($sqlInjectionPatterns, $replaceString, $text);

        return trim(preg_replace('/\s+/', ' ', $cleaned));
    }

    /**
     * Get file category based on file extension
     * @param string|null $fileType File extension (e.g., 'jpg', 'mp4', 'pdf')
     * @return string File category: 'image', 'video', 'audio', 'document', 'file'
     */
    public static function getFileCategory(?string $fileType): string
    {
        if (!$fileType) {
            return 'file';
        }

        $fileType = strtolower($fileType);

        // Image extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'ico', 'tiff', 'tif'];
        if (in_array($fileType, $imageExtensions)) {
            return 'image';
        }

        // Video extensions
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'mpeg', 'mpg', '3gp', 'm4v'];
        if (in_array($fileType, $videoExtensions)) {
            return 'video';
        }

        // Audio extensions
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a', 'flac', 'wma', 'opus', 'webm'];
        if (in_array($fileType, $audioExtensions)) {
            return 'audio';
        }

        // Document extensions
        $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'rtf', 'odt', 'ods', 'odp'];
        if (in_array($fileType, $documentExtensions)) {
            return 'document';
        }

        return 'file';
    }

    /**
     * Get Japanese label for file category
     * @param string $category File category
     * @return string Japanese label
     */
    public static function getFileCategoryLabel(string $category): string
    {
        $labels = [
            'image' => '画像',
            'video' => '動画',
            'audio' => '音声',
            'document' => 'ドキュメント',
            'file' => 'ファイル',
        ];

        return $labels[$category] ?? 'ファイル';
    }
    public static function getCouponCanUse($coupons, $id = '', $userType = '')
    {
        if ($coupons->isEmpty()) {
            return collect([]);
        }
        $couponIds = $coupons->pluck('id');
        $reservations = collect([]);
        $paymentPlans = collect([]);
        if (Auth::guard('member')->check()) {
            $reservations = Reservation::whereIn('status', [ReservationStatus::APPROVED, ReservationStatus::CONFIRM])
                ->where('id', '<>', $id)
                ->whereIn('coupon_id', $couponIds)->get();
            $paymentPlans = PaymentPlan::whereIn('coupon_id', $couponIds)->get();
        }
        $couponCanUse = [];
        foreach ($coupons as $coupon) {
            if ($coupon->limit_type == 1) {
                if ($coupon->limit <= $reservations->where('coupon_id', $coupon->id)->count() + $paymentPlans->where('coupon_id', $coupon->id)->count()) {
                    continue;
                }
            }
            if ($coupon->account_use_type == 1) {
                if ($reservations->where('coupon_id', $coupon->id)->first()) {
                    continue;
                }
                if ($paymentPlans->where('coupon_id', $coupon->id)->first()) {
                    continue;
                }
            }
            if ($coupon->account_use_type == 3) {
                if ($reservations->where('coupon_id', $coupon->id)->count() + $paymentPlans->where('coupon_id', $coupon->id)->count() >= $coupon->maximum_account) {
                    continue;
                }
            }
            if ($userType && $coupon->target_user && !in_array($userType, $coupon->target_user)) {
                continue;
            }
            $couponCanUse[] = $coupon;
        }

        return collect($couponCanUse);
    }

    public static function getUsageOption($shopId, $date)
    {
        $dayOfTheWeek = Carbon::parse($date)->dayOfWeek;
        $dates = [
            Carbon::parse($date)->addDays(-1)->format('Y-m-d'),
            Carbon::parse($date)->format('Y-m-d'),
            Carbon::parse($date)->addDays(1)->format('Y-m-d')
        ];
        $schedule = AvailabilitySlot::whereDate('date', $date)->with('options')->where('location_id', $shopId)->first();
        if (!$schedule) {
            $schedule = Location::where('id', $shopId)->with('options')->first();
        }
        $options = [];

        $reservations = Reservation::whereIn('usage_date', $dates)->whereIn('status', [ReservationStatus::APPROVED, ReservationStatus::CONFIRM])->where('location_id', $shopId)->with('usageSlotOption')->get();
        foreach ($schedule->options->where('is_active', 1)->where('type', self::checkHolidayOrLastWeek($dates[1]) ? 4 : 3)->where('unit', '<>', 4) as $option) {
            switch ($option->unit) {
                case 1:
                    $reservationOld = $reservations->where(function ($q) use ($dates) {
                        return (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[0] && $q->usageOption->unit == 4) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 1) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 3) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 4);
                    });
                    $flagDisabled = $schedule->status == SaunaScheduleStatus::RESERVATIONS_NOT_AVAILABLE || $schedule->status == SaunaScheduleStatus::RESERVATION_FULL;
                    if (!$reservationOld->isEmpty()) {
                        $flagDisabled = true;
                    }
                    $options[] = [
                        'option' => $option,
                        'flagDisabled' => $flagDisabled
                    ];
                    break;
                case 2:
                    $reservationOld = $reservations->where(function ($q) use ($dates) {
                        return Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] &&
                            ($q->usageOption->unit == 3 || $q->usageOption->unit == 2 || $q->usageOption->unit == 4);
                    });
                    $flagDisabled = $schedule->status == SaunaScheduleStatus::RESERVATIONS_NOT_AVAILABLE || $schedule->status == SaunaScheduleStatus::RESERVATION_FULL;
                    if (!$reservationOld->isEmpty()) {
                        $flagDisabled = true;
                    }
                    $options[] = [
                        'option' => $option,
                        'flagDisabled' => $flagDisabled
                    ];
                    break;
                case 3:
                    $reservationOld = $reservations->where(function ($q) use ($dates) {
                        return (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[0] && $q->usageOption->unit == 4) ||
                        (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] &&
                            ($q->usageOption->unit == 3 || $q->usageOption->unit == 2 || $q->usageOption->unit == 4 || $q->usageOption->unit == 1));
                    });
                    $flagDisabled = $schedule->status == SaunaScheduleStatus::RESERVATIONS_NOT_AVAILABLE || $schedule->status == SaunaScheduleStatus::RESERVATION_FULL;
                    if (!$reservationOld->isEmpty()) {
                        $flagDisabled = true;
                    }
                    $options[] = [
                        'option' => $option,
                        'flagDisabled' => $flagDisabled
                    ];
                    break;
            }
        }
        $scheduleNextDay = AvailabilitySlot::whereDate('date', $dates[2])->where('location_id', $shopId)->first();
        foreach ($schedule->options->where('is_active', 1)->where('type', self::checkHolidayOrLastWeek($dates[2]) ? 4 : 3)->where('unit', 4) as $option) {
            switch ($option->unit) {
                case 4:
                    $reservationOld = $reservations->where(function ($q) use ($dates) {
                        return (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[2] && $q->usageOption->unit == 1) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 1) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 2) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 3) ||
                            (Carbon::parse($q->usage_date)->format('Y-m-d') == $dates[1] && $q->usageOption->unit == 4);
                    });
                    $flagDisabled = $scheduleNextDay && ($scheduleNextDay->status == SaunaScheduleStatus::RESERVATIONS_NOT_AVAILABLE || $scheduleNextDay->status == SaunaScheduleStatus::RESERVATION_FULL);
                    if (!$reservationOld->isEmpty()) {
                        $flagDisabled = true;
                    }
                    $options[] = [
                        'option' => $option,
                        'flagDisabled' => $flagDisabled
                    ];
                    break;
            }
        }
        return $options;
    }

    public static function checkHolidayOrLastWeek($date)
    {
        $dayOfTheWeek = Carbon::parse($date)->dayOfWeek;
        if ($dayOfTheWeek == 0 || $dayOfTheWeek == 6 || Holiday::whereDate('date', Carbon::parse($date)->format('Y-m-d'))->exists()) {
            return true;
        }
        return false;
    }

    public static function updateScheduleStatus($shopId, $date)
    {
        $schedule = AvailabilitySlot::whereDate('date', $date)->where('location_id', $shopId)->first();
        if (!$schedule) {
            $shopInfo = Location::where('id', $shopId)->with('options')->first();
            $schedule = new AvailabilitySlot();
            $schedule->location_id = $shopId;
            $schedule->date = $date;
            $schedule->status = SaunaScheduleStatus::AVAILABLE;
            $schedule->parking_flag = $shopInfo->parking_flag;
            $schedule->parking_price = $shopInfo->parking_price;
            $schedule->save();
            foreach ($shopInfo->options ?? [] as $option) {
                SlotOption::updateOrCreate(
                    [
                        'availability_slot_id' => $schedule->id,
                        'type' => $option['type'] ?? null,
                        'unit' => $option['unit'] ?? null,
                    ],
                    [
                        'name'      => $option['name'] ?? null,
                        'price'     => $option['price'] ?? null,
                        'is_active' => $option['is_active'] ?? 0,
                        'user_type' => $option['user_type'] ?? 1,
                    ]
                );
            }
        }
        if ($schedule->status != SaunaScheduleStatus::RESERVATIONS_NOT_AVAILABLE) {
            $optionPrices = collect(self::getUsageOption($shopId, $date));
            $schedule->status = SaunaScheduleStatus::AVAILABLE;
            if ($optionPrices->where('flagDisabled', true)->isEmpty()) {
                $schedule->status = SaunaScheduleStatus::RESERVATIONS_AVAILABLE;
            }
            if ($optionPrices->where('flagDisabled', false)->isEmpty()) {
                $schedule->status = SaunaScheduleStatus::RESERVATION_FULL;
            }
            $schedule->save();
        }
    }

    public static function getTimeCheck($plan)
    {
        $start = Carbon::now();
        $end = null;
        if ($plan->available_to_unit == 1) {
            $start = Carbon::now()->addMinutes($plan->available_to_value);
        } elseif ($plan->available_to_unit == 2) {
            $start = Carbon::now()->addHours($plan->available_to_value);
        } elseif ($plan->available_to_unit == 3) {
            $start = Carbon::parse(Carbon::now()->addDays($plan->available_to_value)->format('Y/m/d 00:00:00'));
        } else {
            $start = Carbon::now()->addMonths($plan->available_to_value);
        }
        if ($plan->available_from_unit == 1) {
            $end = Carbon::now()->addMinutes($plan->available_from_value);
        } elseif ($plan->available_from_unit == 2) {
            $end = Carbon::now()->addHours($plan->available_from_value);
        } elseif ($plan->available_from_unit == 3) {
            $end = Carbon::parse(Carbon::now()->addDays($plan->available_from_value)->format('Y/m/d 23:59:59'));
        } else {
            $end = Carbon::now()->addMonths($plan->available_from_value);
        }
        return [
            'start' => Carbon::parse($start),
            'end' => $end
        ];
    }

    public static function getRemoteLockDeviceList($accessToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('REMOTE_LOCK_ENDPOINT') . '/devices',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $accessToken
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public static function getRemoteLockAccessToken($refreshToken)
    {
        $curl = curl_init();
        $body = 'client_id=' . env('REMOTE_LOCK_CLIENT_ID'). '&client_secret=' .env('REMOTE_LOCK_CLIENT_SECRET') . "&refresh_token={$refreshToken}&grant_type=refresh_token";
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('REMOTE_LOCK_ENDPOINT') . '/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public static function remoteLockBooking($data, $accessToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('REMOTE_LOCK_ENDPOINT') . '/bookings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'type' => 'booking',
                'attributes' => $data
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public static function getSwitchDeviceList($accessToken)
    {
        $t = str_replace('.', '', (string) microtime(true));
        $uuid = \Str::uuid();
        $nonce = (string) $uuid;
        $sign = base64_encode(hash_hmac('sha256', $accessToken . $t . $nonce, env('SWITCH_BOT_SECRET'), true));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SWITCH_BOT_ENDPOINT') . '/v1.1/devices',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'sign: ' . $sign,
                't: ' . $t,
                'nonce: ' . $nonce,
                'Authorization: ' . $accessToken
              ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public static function switchBotCreateKey($accessToken, string $deviceId, $data)
    {
        $t = str_replace('.', '', (string) microtime(true));
        $uuid = \Str::uuid();
        $nonce = (string) $uuid;
        $sign = base64_encode(hash_hmac('sha256', $accessToken . $t . $nonce, env('SWITCH_BOT_SECRET'), true));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('SWITCH_BOT_ENDPOINT') . '/v1.1/devices/' . $deviceId . '/commands',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'sign: ' . $sign,
                't: ' . $t,
                'nonce: ' . $nonce,
                'Content-Type: application/json',
                'Authorization: ' . $accessToken
              ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public static function remoteLockUpdate($id, $data, $accessToken)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('REMOTE_LOCK_ENDPOINT') . '/bookings/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode([
                'type' => 'booking',
                'attributes' => $data
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }
}
