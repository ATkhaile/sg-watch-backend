<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Http\Requests\Api\ApiFormRequest;

class ImportComputersRequest extends ApiFormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
      'brand_id' => ['nullable', 'integer', 'exists:shop_brands,id'],
      'category_id' => ['nullable', 'integer', 'exists:shop_categories,id'],
    ];
  }
}
