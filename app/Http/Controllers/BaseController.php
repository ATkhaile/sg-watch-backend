<?php

namespace App\Http\Controllers;

use App\Enums\OptionUserType;
use App\Enums\PlanStatus;
use App\Enums\ReservationStatus;
use App\Enums\ShopType;
use App\Models\Holiday;
use App\Models\PlanReservationRule;
use App\Models\Reservation;
use App\Models\Location;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class BaseController extends Controller
{
    public function setFlash($message, $mode = 'success', $urlRedirect = '')
    {
        session()->forget('Message.flash');
        session()->push('Message.flash', [
            'message' => $message,
            'mode' => $mode,
            'urlRedirect' => $urlRedirect,
        ]);
    }

    public static function newListLimit($query)
    {
        $newSizeLimit = PAGE_SIZE_DEFAULT;
        $arrPageSize = PAGE_SIZE_LIMIT;
        if (isset($query['limit_page'])) {
            $newSizeLimit = (($query['limit_page'] === '') || ! in_array($query['limit_page'], $arrPageSize)) ? $newSizeLimit : $query['limit_page'];
        }
        if (((isset($query['limit_page']))) && (! empty($query->query('limit_page')))) {
            $newSizeLimit = (! in_array($query->query('limit_page'), $arrPageSize)) ? $newSizeLimit : $query->query('limit_page');
        }

        return $newSizeLimit;
    }

    public static function newListLimitForUser($query)
    {
        return $query['per_page'];
        // $newSizeLimit = PAGE_SIZE_DEFAULT;
        // $arrPageSize = PAGE_SIZE_LIMIT;
        // if (isset($query['per_page'])) {
        //     $newSizeLimit = (($query['per_page'] === '') || !in_array($query['per_page'], $arrPageSize)) ? $newSizeLimit : $query['per_page'];
        // }
        // if (((isset($query['per_page']))) && (!empty($query->query('per_page')))) {
        //     $newSizeLimit = (!in_array($query->query('per_page'), $arrPageSize)) ? $newSizeLimit : $query->query('per_page');
        // }
        // return $newSizeLimit;
    }

    /**
     * [escapeLikeSentence description]
     * @param  [type]  $str    :search conditions
     * @param  bool $before : add % before
     * @param  bool $after  : add % after
     * @return [type]          [description]
     */
    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return [[$column, 'LIKE', (($before) ? '%' : '').$result.(($after) ? '%' : '')]];
    }

    public function handleSearchQuery($str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mbTrim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_

        return (($before) ? '%' : '').$result.(($after) ? '%' : '');
    }

    public function mbTrim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);

        return $ret;
    }

    /**
     * マルチバイト対応のtrim
     *
     * 文字列先頭および最後の空白文字を取り除く
     *
     * @param  string  空白文字を取り除く文字列
     * @return  string
     */
    public function checkRfidCode($rfidCode)
    {
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9]*$/i', $rfidCode);
    }

    public function checkReturnCsvContent($column)
    {
        $ret = 0;
        if ($column == '') {
            $ret = 1; // Blank OR NULL
        } elseif (! $this->checkRfidCode($column)) {
            $ret = 2; // Error formart
        }

        return $ret;
    }

    public function logInfo($request, $message = '')
    {
        Log::channel('access_log')->info([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function logError($request, $message = '')
    {
        Log::channel('access_log')->error([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function logWarning($request, $message = '')
    {
        Log::channel('access_log')->warning([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function convertShijis($text)
    {
        return mb_convert_encoding($text, 'SJIS', 'UTF-8');
    }

    public function convertSjisToUtf8($text)
    {
        return mb_convert_encoding($text, 'UTF-8', 'SJIS');
    }


    public function getData($line, $column)
    {
        return $this->removeBomUtf8($this->multibyteTrim(($line[$column] != 'None' && $line[$column] != '') ? $line[$column] : ''));
    }

    public function removeBomUtf8($s)
    {
        if (substr($s, 0, 3) == chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))) {
            return substr($s, 3);
        }

        return $s;
    }

    public function multibyteTrim($str)
    {
        if (! function_exists('mb_trim') || ! extension_loaded('mbstring')) {
            return preg_replace("/(^\s+)|(\s+$)/u", '', $str);
        }

        return mb_trim($str);
    }

    public function mergeSession($data)
    {
        $dataSession = '';
        if (session()->get('Message.flash')) {
            $dataSession = session()->get('Message.flash')[0];
            session()->forget('Message.flash');
        }

        return array_merge($data, [
            'dataSession' => $dataSession,
        ]);
    }

    public function sortLinks($routeName, $data, $request)
    {
        $link = [];
        $dataParam = $request->all();
        unset($dataParam['sort']);
        unset($dataParam['direction']);
        foreach ($data as $key => $value) {
            $dataParam['sort'] = $value['key'];
            $dataParam['direction'] = $request->sort != $value['key'] ? 'asc' : ($request->direction == 'asc' ? 'desc' : 'asc');
            $link[] = [
                'link' => route($routeName, $dataParam),
                'name' => $value['name'],
                'iconDirection' => $request->sort != $value['key'] ? 'pi-sort' : ($request->direction == 'asc' ? 'pi-sort-alpha-down' : 'pi-sort-alpha-down-alt'),
            ];
        }

        return $link;
    }

    public function paginator($data)
    {
        $url = [0 => $data->url(1)];
        foreach (range(1, $data->lastPage()) as $key => $i) {
            $url[$key] = $data->url($i);
        }

        return [
            'firstItem' => $data->firstItem(),
            'end' => $data->perPage() * ($data->currentPage() - 1) + $data->count(),
            'total' => $data->total(),
            'onFirstPage' => $data->onFirstPage(),
            'previousPageUrl' => $data->previousPageUrl(),
            'lastPage' => $data->lastPage(),
            'hasMorePages' => $data->hasMorePages(),
            'currentPage' => $data->currentPage(),
            'nextPageUrl' => $data->nextPageUrl(),
            'url' => $url,
        ];
    }

    public function currentUserId()
    {
        return '8001';
        // return auth()->user()->id;
    }

    public function holdingIssue()
    {
        return '8001';
    }

    public function OwnInvestor()
    {
        return '05HS53-E';
    }

    public function OwnFund()
    {
        return '04GL9D-E';
    }

    public function OwnFm()
    {
        return '04GL9D-E';
    }

    public function OwnAnalyst()
    {
        return '002BSH-E';
    }


    public function checkPaginatorList($query)
    {
        if ($query->currentPage() > $query->lastPage()) {
            return true;
        }

        return false;
    }

    public function sendResponse($data, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($message, $data = [], $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function calcTotalPrice($request, $shopInfo, $shopId, $coupons, $reservation = null, $usageOptions = null)
    {
        $totalPrice = 0;
        $totalDiscount = 0;
        $coupon = $coupons->where('id', $request->coupon_id)->first();
        $shopType = Location::where('id', $shopId)->first();
        if ($shopType->location_type == ShopType::SAUNA) {
            $usageOption = collect($usageOptions)->where('option.id', $request->usage_option_id)->first();
            $totalPrice += $usageOption['option']->price;

            if ($coupon && $coupon->discount_option_type == 2) {
                if ($coupon->coupon_type == 1) {
                    $totalDiscount = $coupon->discount;
                    $totalPrice -= $totalDiscount;
                } else {
                    $totalDiscount = round(($totalPrice * $coupon->discount) / 100);
                    $totalPrice -= $totalDiscount;
                }
            }
            $optionPrice = 0;
            if ($request->parking_flag && $shopInfo->parking_flag) {
                $optionPrice += $shopInfo->parking_price;
            }

            if ($request->option_type1_id) {
                $option = $shopInfo->options->where('id', $request->option_type1_id)->first();
                if ($option) {
                    $optionPrice += $option->price;
                }
            }
            if ($request->option_type2_id) {
                $option = $shopInfo->options->where('id', $request->option_type2_id)->first();
                if ($option) {
                    $optionPrice += $option->price;
                }
            }

            if ($coupon && $coupon->discount_option_type == 3) {
                if ($coupon->coupon_type == 1) {
                    $totalDiscount = $coupon->discount;
                    $optionPrice -= $totalDiscount;
                } else {
                    $totalDiscount = round(($optionPrice * $coupon->discount) / 100);
                    $optionPrice -= $totalDiscount;
                }
            }
            $totalPrice += $optionPrice;

            if ($coupon && $coupon->discount_option_type == 1) {
                if ($coupon->coupon_type == 1) {
                    $totalDiscount = $coupon->discount;
                    $totalPrice -= $totalDiscount;
                } else {
                    $totalDiscount = round(($totalPrice * $coupon->discount) / 100);
                    $totalPrice -= $totalDiscount;
                }
            }

            return [
                'totalPrice' => $totalPrice < 0 ? 0 : $totalPrice,
                'totalDiscount' => $totalDiscount,
            ];
        }
        $shopInfoOriginal = $shopType;
        $userType = Reservation::where([
            ['account_id', $reservation ? $reservation->account_id : FacadesAuth::guard('member')->user()->id],
            // ['id', '<>', $request->id],
            ['location_id', $shopId]
        ])->whereIn('status', [ReservationStatus::APPROVED, ReservationStatus::CONFIRM])->exists() ? OptionUserType::VISITOR : OptionUserType::EXPERIENCE;
        if ($userType == OptionUserType::EXPERIENCE) {
            $userType = $request->user_type;
        }
        $reservationInfo = null;
        // if ($request->id) {
        //     $reservationInfo = Reservation::where('id', $request->id)->first();
        // }
        $plans = PlanReservationRule::join('user_plans', 'user_plans.plan_reservation_rule_id', 'plan_reservation_rules.id')
            ->join('users', 'users.id', '=', 'user_plans.user_id')
            ->whereIn('status', [PlanStatus::ACTIVE, PlanStatus::LIMITED, PlanStatus::UN_ACTIVE])
            ->where([
                ['users.payment_status', 1],
                // ['status', PlanStatus::ACTIVE],
                ['user_plans.user_id', FacadesAuth::guard('member')->id()],
                ['expire_end', '>', Carbon::now()],
                ['plan_reservation_rules.location_id', $shopId]
                // ['count_remaining', '>', 0]
            ])
            ->where(function ($q) use ($reservationInfo) {
                $q->orWhere('count_remaining', '>', 0);
                if ($reservationInfo) {
                    $q->orWhere('user_plans.id', $reservationInfo->user_plan_id);
                }
            })
            ->select(['plan_reservation_rules.*', 'user_plans.count_remaining', 'user_plans.expire_end', 'user_plans.id as user_plan_id'])->get();
        $planUse = null;
        $hour = Carbon::parse($request->date . ' ' . $request->start_time)->format('H');
        $dayOfTheWeek = Carbon::parse($request->date . ' ' . $request->start_time)->dayOfWeek;
        if ($request->is_plan || ($reservationInfo && $reservationInfo->user_plan_id)) {
            foreach ($plans as $plan) {
                $reservations = Reservation::where('user_plan_id', $plan->user_plan_id)->where('status', ReservationStatus::APPROVED)->where('location_id', $shopId)->get();
                $totalUseFeature = $reservations->where(function($q) use ($plan) {
                    return Carbon::parse($plan->expire_end) < Carbon::parse(Carbon::parse($q->usage_date)->format('Y-m-d') . ' ' . Carbon::parse($q->usage_time_start)->format('H:i'));
                })->count();
                $limit = $plan->no_limit ? 99999 : $plan->limit;
                // dd($limit, $totalUseFeature);
                if (Carbon::parse($plan->start_time)->format('H') <= (int)$hour &&
                    Carbon::parse($plan->end_time)->format('H') >= (int)$hour &&
                    Carbon::parse($plan->expire_end)->addMonthsWithNoOverflow($totalUseFeature >= $limit ? 0 : 1) > Carbon::parse($request->date . ' ' . $request->start_time)) {
                    // Carbon::parse($plan->expire_end)->format('Y') == Carbon::parse($request->date)->format('Y') &&
                    // Carbon::parse($plan->expire_end)->format('m') == Carbon::parse($request->date)->format('m')) {
                    // if ($dayOfTheWeek == 0 || $dayOfTheWeek == 6) {
                    if ($dayOfTheWeek == 0 || $dayOfTheWeek == 6 || Holiday::whereDate('date', Carbon::parse($request->date)->format('Y-m-d'))->exists()) {
                        if (in_array(2, $plan->available_reservation)) {
                            $planUse = $plan;
                        }
                    } else {
                        if (in_array(1, $plan->available_reservation)) {
                            $planUse = $plan;
                        }
                    }
                }
            }
            if ($planUse) {
                $userType = OptionUserType::SUBSCRIPTION;
            }
        }
        //     let tmp =
        //   state.price.options.find((x) => x.id == state.model.option_type1_id)
        //     .price *
        //   state.price.options.find((x) => x.id == state.model.option_type1_id).unit;
        // if (state.plan) {
        //   tmp =
        //     state.price.options.find((x) => x.id == state.model.option_type1_id)
        //       .price *
        //     (state.price.options.find((x) => x.id == state.model.option_type1_id)
        //       .unit -
        //       state.plan.accompanying_slots);
        // }
        // price += tmp < 0 ? 0 : tmp;
        $option = $shopInfo->options->where('user_type', $userType)->where('type', 1)->where('id', $request->option_type1_id)->first();
        $totalPerson = 0;
        if ($option) {
            $totalPerson = $option->unit;
            if (!$request->is_extend) {
                if ($planUse) {
                    if (($option->unit - 1) > $planUse->accompanying_slots) {
                        $totalPrice += ($option->unit - $planUse->accompanying_slots - 1) * $planUse->charge_people_price;
                    }
                } else {
                    $totalPrice += $option->price;
                }
            }
        }
        if ($coupon && $coupon->discount_option_type == 2) {
            if ($coupon->coupon_type == 1) {
                $totalDiscount = $coupon->discount;
                $totalPrice -= $totalDiscount;
            } else {
                $totalDiscount = round(($totalPrice * $coupon->discount) / 100);
                $totalPrice -= $totalDiscount;
            }
        }
        $optionPrice = 0;

        if ($request->option_type2_id) {
            $option = $shopInfo->options->where('user_type', $userType)->where('type', 2)->where('id', $request->option_type2_id)->first();
            // if ($option) {
            //     $optionPrice += $option->price * $totalPerson;
            // }
            if ($planUse) {
                $optionPrice += $planUse->{'charge_time_price_'.$option->unit} * $totalPerson;
            } else {
                $optionPrice += $option->price * $totalPerson;
            }
        }

        if ($request->parking_flag && $shopInfoOriginal->parking_flag) {
            $optionPrice += $shopInfoOriginal->parking_price;
        }
        if ($request->instructor_flag && $shopInfoOriginal->{'instructor_flag_' . $userType}) {
            $optionPrice += $shopInfoOriginal->{'instructor_price_' . $userType};
        }
        if ($request->lesson_flag && $shopInfoOriginal->{'lesson_flag_' . $userType}) {
            $optionPrice += $shopInfoOriginal->{'lesson_price_' . $userType};
        }
        if ($coupon && $coupon->discount_option_type == 3) {
            if ($coupon->coupon_type == 1) {
                $totalDiscount = $coupon->discount;
                $optionPrice -= $totalDiscount;
            } else {
                $totalDiscount = round(($optionPrice * $coupon->discount) / 100);
                $optionPrice -= $totalDiscount;
            }
        }

        $totalPrice += $optionPrice;

        if ($coupon && $coupon->discount_option_type == 1) {
            if ($coupon->coupon_type == 1) {
                $totalDiscount = $coupon->discount;
                $totalPrice -= $totalDiscount;
            } else {
                $totalDiscount = round(($totalPrice * $coupon->discount) / 100);
                $totalPrice -= $totalDiscount;
            }
        }

        return [
            'totalPrice' => $totalPrice < 0 ? 0 : $totalPrice,
            'totalDiscount' => $totalDiscount,
        ];
    }

    public function timeSettings($shopInfo)
    {
        $minTimeLesson = Carbon::now()->format('Y/m/d H:i:s');
        switch ($shopInfo->lesson_setting_unit) {
            case 1:
                $minTimeLesson = Carbon::now()->addMinutes($shopInfo->lesson_setting_value)->format('Y/m/d H:i:s');
                break;
            case 2:
                $minTimeLesson = Carbon::now()->addHours($shopInfo->lesson_setting_value)->format('Y/m/d H:i:s');
                break;
            case 3:
                $minTimeLesson = Carbon::now()->addDays($shopInfo->lesson_setting_value)->endOfDay()->format('Y/m/d H:i:s');
                break;
        }
        $minTimeInstructor = Carbon::now()->format('Y/m/d H:i:s');
        switch ($shopInfo->instructor_setting_unit) {
            case 1:
                $minTimeInstructor = Carbon::now()->addMinutes($shopInfo->instructor_setting_value)->format('Y/m/d H:i:s');
                break;
            case 2:
                $minTimeInstructor = Carbon::now()->addHours($shopInfo->instructor_setting_value)->format('Y/m/d H:i:s');
                break;
            case 3:
                $minTimeInstructor = Carbon::now()->addDays($shopInfo->instructor_setting_value)->endOfDay()->format('Y/m/d H:i:s');
                break;
        }
        return [
            'minTimeLesson' => $minTimeLesson,
            'minTimeInstructor' => $minTimeInstructor,
        ];
    }

    public function generateUniqueInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(6)) . '-' . Str::upper(Str::random(6));
        } while (DB::table('users')->where('invite_code', $code)->exists());

        return $code;
    }
}
