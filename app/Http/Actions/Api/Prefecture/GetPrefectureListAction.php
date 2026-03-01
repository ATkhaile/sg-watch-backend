<?php

namespace App\Http\Actions\Api\Prefecture;

use App\Http\Controllers\BaseController;
use App\Models\Prefecture;

class GetPrefectureListAction extends BaseController
{
    public function __invoke()
    {
        $prefectures = Prefecture::orderBy('order_num')
            ->get(['prefecture_id', 'name']);

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
            'data' => [
                'prefectures' => $prefectures,
            ],
        ]);
    }
}
