<?php

namespace App\Imports;

use App\Models\Shop\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShopIpadImport implements ToModel, WithStartRow
{
  private ?int $categoryId;
  private ?int $brandId;
  private int $nextDisplayOrder = 1;

  // Thứ tự cột trong file Excel (bắt đầu từ 0)
  private const COL_TEN_MAY            = 1;
  private const COL_NAM_SAN_XUAT       = 2;
  private const COL_MAU_MAY            = 3;
  private const COL_MO_TA_NGAN         = 4;
  private const COL_THONG_TIN_SP       = 5;
  private const COL_MO_TA_CHI_TIET     = 6;
  private const COL_BAO_MAT            = 7;
  private const COL_PIN                = 8;
  private const COL_GIA_BAN_YEN        = 9;
  private const COL_GIA_NIEM_YET_YEN   = 10;
  private const COL_GIA_NHAP_YEN       = 11;
  private const COL_POINT              = 12;
  private const COL_SO_LUONG           = 13;

  public function __construct(?int $categoryId = null, ?int $brandId = null)
  {
    $this->categoryId = $categoryId;
    $this->brandId = $brandId;

    $maxOrder = Product::withTrashed()->max('display_order') ?? 0;
    $this->nextDisplayOrder = $maxOrder + 1;
  }

  public function startRow(): int
  {
    return 2;
  }

  public function model(array $row)
  {
    $name = $this->val($row, self::COL_TEN_MAY);

    // Bỏ qua row nếu không có tên
    if (!$name) {
      return null;
    }

    $sku = 'IPAD-' . strtoupper(Str::random(8));

    $priceJpy = $this->parsePrice($this->val($row, self::COL_GIA_BAN_YEN));
    $originalPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NIEM_YET_YEN));
    $costPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NHAP_YEN));

    $attributes = array_filter([
      'year' => $this->val($row, self::COL_NAM_SAN_XUAT),
      'color' => $this->val($row, self::COL_MAU_MAY),
      'security' => $this->val($row, self::COL_BAO_MAT),
      'battery' => $this->val($row, self::COL_PIN),
    ], fn($v) => $v !== null && $v !== '');

    return new Product([
      'category_id' => $this->categoryId,
      'brand_id' => $this->brandId,
      'name' => $name,
      'slug' => Str::slug($name) . '-' . Str::random(5),
      'sku' => $sku,
      'short_description' => $this->val($row, self::COL_MO_TA_NGAN),
      'product_info' => $this->val($row, self::COL_THONG_TIN_SP),
      'description' => $this->val($row, self::COL_MO_TA_CHI_TIET),
      'price_jpy' => $priceJpy,
      'original_price_jpy' => $originalPriceJpy > 0 ? $originalPriceJpy : null,
      'cost_price_jpy' => $costPriceJpy > 0 ? $costPriceJpy : null,
      'points' => (int) ($this->val($row, self::COL_POINT) ?? 0),
      'stock_quantity' => (int) ($this->val($row, self::COL_SO_LUONG) ?? 0),
      'attributes' => !empty($attributes) ? $attributes : null,
      'is_active' => true,
      'condition' => 'used',
      'display_order' => $this->nextDisplayOrder++,
    ]);
  }

  private function val(array $row, int $index): ?string
  {
    $value = $row[$index] ?? null;
    if ($value === null || $value === '') {
      return null;
    }
    return (string) $value;
  }

  private function parsePrice($value): float
  {
    if (!$value) return 0;
    $clean = preg_replace('/[^0-9]/', '', (string) $value);
    return (float) $clean;
  }
}
