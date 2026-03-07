<?php

namespace App\Imports;

use App\Models\Shop\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShopComputerImport implements ToModel, WithHeadingRow
{
  private ?int $categoryId;
  private ?int $brandId;
  private int $nextDisplayOrder = 1;

  public function __construct(?int $categoryId = null, ?int $brandId = null)
  {
    $this->categoryId = $categoryId;
    $this->brandId = $brandId;

    $maxOrder = Product::withTrashed()->max('display_order') ?? 0;
    $this->nextDisplayOrder = $maxOrder + 1;
  }

  public function model(array $row)
  {
    $name = $row['ten_laptop'] ?? null;
    if (!$name) {
      return null;
    }

    $sku = 'PC-' . strtoupper(Str::random(8));
    $slug = Str::slug($name) . '-' . Str::random(5);

    // Xử lý giá tiền
    $priceJpy = $this->parsePrice($row['gia_banyen'] ?? '0');
    $originalPriceJpy = $this->parsePrice($row['gia_niem_yet_yen'] ?? '0');
    $costPriceJpy = $this->parsePrice($row['gia_nhap_yen'] ?? '0');

    // Gom các thông tin riêng của laptop vào attributes
    $attributes = array_filter([
      'year' => $row['nam_san_xuat'] ?? null,
      'color' => $row['mau_may'] ?? null,
      'specs' => $row['cau_hinh'] ?? null,
      'gpu' => $row['cad_do_hoa_gpu'] ?? null,
      'ports' => $row['cong_ket_noi'] ?? null,
      'target_customer' => $row['huong_toi_khach_hang'] ?? null,
      'design' => $row['thiet_ke'] ?? null,
      'battery' => $row['pin'] ?? null,
      'advantages' => $row['uu_diem'] ?? null,
    ], fn($v) => $v !== null && $v !== '');

    $product = new Product([
      'category_id' => $this->categoryId,
      'brand_id' => $this->brandId,
      'name' => $name,
      'slug' => $slug,
      'sku' => $sku,
      'short_description' => $row['mo_ta_ngan'] ?? null,
      'description' => $row['mo_ta_chi_tiet'] ?? null,
      'price_jpy' => $priceJpy,
      'original_price_jpy' => $originalPriceJpy > 0 ? $originalPriceJpy : null,
      'cost_price_jpy' => $costPriceJpy > 0 ? $costPriceJpy : null,
      'attributes' => !empty($attributes) ? $attributes : null,
      'is_active' => true,
      'condition' => 'used',
      'display_order' => $this->nextDisplayOrder++,
    ]);

    return $product;
  }

  private function parsePrice($value): float
  {
    if (!$value) return 0;
    $clean = preg_replace('/[^0-9]/', '', (string) $value);
    return (float) $clean;
  }
}
