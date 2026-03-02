<?php

namespace App\Http\Actions\Api\Prefecture;

use App\Http\Controllers\BaseController;
use App\Models\Prefecture;
use Illuminate\Http\Request;

class GetPrefectureListAction extends BaseController
{
    public function __invoke(Request $request)
    {
        $query = Prefecture::orderBy('order_num');

        if ($name = $request->query('name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $prefectures = $query->get(['prefecture_id', 'name']);

        return response()->json([
            'message' => 'success',
            'status_code' => 200,
            'data' => [
                'prefectures' => $prefectures,
            ],
        ]);
    }
}
