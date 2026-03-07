<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Http\Controllers\BaseController;
use App\Imports\ShopComputerImport;
use App\Http\Requests\Api\ShopProduct\ImportComputersRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ImportComputersAction extends BaseController
{
  public function __invoke(ImportComputersRequest $request): JsonResponse
  {
    try {
      Excel::import(
        new ShopComputerImport($request->input('category_id'), $request->input('brand_id')),
        $request->file('file')
      );

      return response()->json([
        'status' => 'success',
        'message' => 'Import máy tính thành công.',
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
