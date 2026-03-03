<?php

namespace App\Http\Actions\Api\ShopProduct;

use App\Http\Controllers\BaseController;
use App\Imports\ShopProductImport;
use App\Http\Requests\Api\ShopProduct\ImportProductsRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsAction extends BaseController
{
  /**
   * Import products from an Excel file.
   *
   * @param ImportProductsRequest $request
   * @return JsonResponse
   */
  public function __invoke(ImportProductsRequest $request): JsonResponse
  {
    // $data = Excel::toArray(new ShopProductImport, $request->file('file'));
    // dd($data);

    try {
      Excel::import(new ShopProductImport, $request->file('file'));

      return response()->json([
        'status' => 'success',
        'message' => 'Import sản phẩm thành công.',
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
