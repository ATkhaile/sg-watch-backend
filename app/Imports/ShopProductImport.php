<?php

namespace App\Imports;

use App\Models\Shop\Brand;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShopProductImport implements ToModel, WithHeadingRow
{
  private ?int $categoryId = null;
  private int $nextDisplayOrder = 1;

  public function __construct()
  {
    // Find or create a fixed category "Đồng hồ"
    $category = Category::firstOrCreate(
      ['slug' => 'dong-ho'],
      [
        'name' => 'Đồng hồ',
        'slug' => 'dong-ho',
        'is_active' => true,
      ]
    );
    $this->categoryId = $category->id;

    // Initialize next display order to be at the end of the existing list
    $maxOrder = Product::withTrashed()->max('display_order') ?? 0;
    $this->nextDisplayOrder = $maxOrder + 1;
  }

  public function model(array $row)
  {
    // 1: Nếu Product Code (SKU) đã tồn tại trong hệ thống thì Bỏ qua (Skip)
    $sku = $row['ma_san_pham'] ?? null;
    if (!$sku || Product::where('sku', $sku)->exists()) {
      return null;
    }

    // 3: Nếu tên Thương hiệu trong file Excel chưa có trong database hãy tự động tạo mới
    $brandName = $row['hang_dong_ho'] ?? 'Other';
    $brand = Brand::firstOrCreate(
      ['slug' => Str::slug($brandName)],
      [
        'name' => $brandName,
        'slug' => Str::slug($brandName),
        'is_active' => true,
      ]
    );

    // Xử lý giá tiền (Xóa ¥ và dấu chấm phân cách hàng nghìn)
    $priceJpy = $this->parsePrice($row['gia_yen'] ?? '0');
    $originalPriceJpy = $this->parsePrice($row['gia_goc_yen'] ?? '0');
    $costPriceJpy = $this->parsePrice($row['gia_nhap'] ?? '0');

    // Xử lý bảo hành (VD: "5 Năm" -> 60)
    $warrantyMonths = $this->parseWarranty($row['so_thang_bao_hanh'] ?? '');

    // Xử lý giới tính
    $gender = $this->mapGender($row['gioi_tinh'] ?? '');

    $product = new Product([
      'category_id' => $this->categoryId,
      'brand_id' => $brand->id,
      'name' => $row['ten_dong_ho'],
      'slug' => Str::slug($row['ten_dong_ho']) . '-' . Str::random(5),
      'sku' => $sku,
      'short_description' => $row['mo_ta_ngan'] ?? null,
      'description' => $row['mo_ta_chi_tiet'] ?? null,
      'product_info' => $row['thong_tin_san_pham'] ?? null,
      'deal_info' => $row['thong_tin_khuyen_mai'] ?? null,
      'price_jpy' => $priceJpy,
      'original_price_jpy' => $originalPriceJpy > 0 ? $originalPriceJpy : null,
      'cost_price_jpy' => $costPriceJpy > 0 ? $costPriceJpy : null,
      'points' => (int) ($row['diem_thuong'] ?? 0),
      'gender' => $gender,
      'movement_type' => $this->mapMovementType($row['loai_pinco'] ?? ''),
      'stock_quantity' => (int) ($row['so_luong_hang_con'] ?? 0),
      'warranty_months' => $warrantyMonths,
      'is_active' => true,
      'condition' => 'new',
      'display_order' => $this->nextDisplayOrder++,
    ]);

    return $product;
  }

  private function parsePrice($value): float
  {
    if (!$value)
      return 0;
    // Xóa tất cả ký tự không phải số (trừ dấu chấm thập phân nếu có)
    // Lưu ý: Ở NB thường dùng dấu chấm để phân cách hàng nghìn trong file text, nên ta xóa cả dấu chấm
    $clean = preg_replace('/[^0-9]/', '', (string) $value);
    return (float) $clean;
  }

  private function parseWarranty(string $value): int
  {
    $value = strtolower($value);
    if (str_contains($value, 'năm')) {
      $years = (int) preg_replace('/[^0-9]/', '', $value);
      return $years * 12;
    }
    return (int) preg_replace('/[^0-9]/', '', $value);
  }

  private function mapGender(string $value): ?string
  {
    $value = strtolower($value);
    if (str_contains($value, 'nam'))
      return 'male';
    if (str_contains($value, 'nữ'))
      return 'female';
    return 'unisex';
  }

  private function mapMovementType(string $value): ?string
  {
    $value = strtolower($value);
    if (str_contains($value, 'pin') || str_contains($value, 'quartz') || str_contains($value, 'battery')) {
      return 'quartz';
    }
    if (str_contains($value, 'cơ') || str_contains($value, 'automatic') || str_contains($value, 'mechanical')) {
      return 'automatic';
    }
    return null;
  }
}
