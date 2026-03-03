<?php

namespace App\Http\Requests\Api\ShopProduct;

use App\Http\Requests\Api\ApiFormRequest;

class ImportProductsRequest extends ApiFormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'], // Max 10MB
    ];
  }
}
