<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Http\Controllers\BaseController;
use App\Imports\ShopIpadImport;
use App\Http\Requests\Api\ShopProduct\ImportIpadsRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ImportIpadsAction extends BaseController
{
  public function __invoke(ImportIpadsRequest $request): JsonResponse
  {
    // $data = Excel::toArray(new ShopIpadImport($request->input('category_id'), $request->input('brand_id')), $request->file('file'));
    // dd($data);

    try {
      Excel::import(
        new ShopIpadImport($request->input('category_id'), $request->input('brand_id')),
        $request->file('file')
      );

      return response()->json([
        'status' => 'success',
        'message' => 'Import iPad thành công.',
        'code' => 200,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Lỗi khi import: ' . $e->getMessage(),
        'code' => 500,
      ], 500);
    }
  }
}
