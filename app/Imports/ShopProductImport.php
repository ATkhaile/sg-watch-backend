<?php

namespace App\Imports;

use App\Models\Shop\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShopProductImport implements ToModel, WithStartRow
{
  private int $categoryId;
  private int $brandId;
  private int $nextDisplayOrder = 1;

  // Thứ tự cột trong file Excel (bắt đầu từ 0)
  private const COL_DANH_MUC             = 0;
  private const COL_TEN_DONG_HO          = 1;
  private const COL_MA_SAN_PHAM          = 2;
  private const COL_HANG_DONG_HO         = 3;
  private const COL_NOI_DIA              = 4;
  private const COL_HANG_ORDER           = 5;
  private const COL_TINH_TRANG           = 6;
  private const COL_MO_TA_NGAN           = 7;
  private const COL_THONG_TIN_SP         = 8;
  private const COL_GOI_Y_SU_DUNG        = 9;
  private const COL_THONG_SO_KY_THUAT    = 10;
  private const COL_KHUYEN_MAI           = 11;
  private const COL_GIA_BAN_YEN          = 12;
  private const COL_GIA_NHAP_YEN         = 13;
  private const COL_GIA_NIEM_YET_YEN     = 14;
  private const COL_GIOI_TINH            = 15;
  private const COL_LOAI_MAY             = 16;
  private const COL_SO_LUONG             = 17;
  private const COL_BAO_HANH             = 18;
  private const COL_ANH                  = 19;

  public function __construct(int $categoryId, int $brandId)
  {
    $this->categoryId = $categoryId;
    $this->brandId = $brandId;

    $maxOrder = Product::withTrashed()->max('display_order') ?? 0;
    $this->nextDisplayOrder = $maxOrder + 1;
  }

  public function startRow(): int
  {
    return 2; // Bỏ qua row header
  }

  public function model(array $row)
  {
    $name = $this->val($row, self::COL_TEN_DONG_HO);
    $sku  = $this->val($row, self::COL_MA_SAN_PHAM);

    // Bỏ qua row nếu không có tên và mã sản phẩm
    if (!$name && !$sku) {
      return null;
    }

    // Nếu SKU đã tồn tại trong hệ thống thì bỏ qua
    if ($sku && Product::where('sku', $sku)->exists()) {
      return null;
    }
    // Nếu không có SKU thì tự sinh mã ngẫu nhiên
    if (!$sku) {
      $sku = 'SKU-' . strtoupper(Str::random(8));
    }

    $priceJpy = $this->parsePrice($this->val($row, self::COL_GIA_BAN_YEN));
    $originalPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NIEM_YET_YEN));
    $costPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NHAP_YEN));
    $warrantyMonths = $this->parseWarranty((string) $this->val($row, self::COL_BAO_HANH));
    $gender = $this->mapGender((string) $this->val($row, self::COL_GIOI_TINH));
    $isDomestic = $this->mapIsDomestic($this->val($row, self::COL_NOI_DIA));
    $stockType = $this->mapStockType($this->val($row, self::COL_HANG_ORDER));
    $condition = $this->mapCondition($this->val($row, self::COL_TINH_TRANG));
    $thongSoKyThuat = $this->val($row, self::COL_THONG_SO_KY_THUAT);

    return new Product([
      'category_id' => $this->categoryId,
      'brand_id' => $this->brandId,
      'name' => $name,
      'slug' => Str::slug($name) . '-' . Str::random(5),
      'sku' => $sku,
      'short_description' => $this->val($row, self::COL_MO_TA_NGAN),
      'description' => $this->val($row, self::COL_GOI_Y_SU_DUNG),
      'product_info' => $this->val($row, self::COL_THONG_TIN_SP),
      'deal_info' => $this->val($row, self::COL_KHUYEN_MAI),
      'attributes' => $thongSoKyThuat ? ['thong_so_ky_thuat' => $thongSoKyThuat] : null,
      'price_jpy' => $priceJpy,
      'original_price_jpy' => $originalPriceJpy > 0 ? $originalPriceJpy : null,
      'cost_price_jpy' => $costPriceJpy > 0 ? $costPriceJpy : null,
      'gender' => $gender,
      'movement_type' => $this->mapMovementType((string) $this->val($row, self::COL_LOAI_MAY)),
      'stock_quantity' => (int) ($this->val($row, self::COL_SO_LUONG) ?? 0),
      'stock_type' => $stockType,
      'warranty_months' => $warrantyMonths,
      'is_active' => true,
      'is_domestic' => $isDomestic,
      'is_new' => $condition === 'new',
      'condition' => $condition,
      'display_order' => $this->nextDisplayOrder++,
    ]);
  }

  /**
   * Lấy giá trị cột, trả về null nếu không tồn tại hoặc rỗng.
   */
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
    if (str_contains($value, 'nam')) return 'male';
    if (str_contains($value, 'nữ')) return 'female';
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

  private function mapCondition(?string $value): string
  {
    if (!$value) return 'new';
    $value = strtolower(trim($value));
    if (str_contains($value, 'cũ') || str_contains($value, 'used') || str_contains($value, 'đã sử dụng')) {
      return 'used';
    }
    if (str_contains($value, 'trưng bày') || str_contains($value, 'display')) {
      return 'display';
    }
    return 'new';
  }

  private function mapIsDomestic(?string $value): bool
  {
    if (!$value) return false;
    $value = strtolower(trim($value));
    return str_contains($value, 'có') || str_contains($value, 'nội địa') || str_contains($value, 'yes') || $value === '1';
  }

  private function mapStockType(?string $value): string
  {
    if (!$value) return 'in_stock';
    $value = strtolower(trim($value));
    if (str_contains($value, 'order') || str_contains($value, 'đặt')) {
      return 'pre_order';
    }
    return 'in_stock';
  }
}
