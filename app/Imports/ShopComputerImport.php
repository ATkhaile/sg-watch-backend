<?php

namespace App\Imports;

use App\Models\Shop\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShopComputerImport implements ToModel, WithStartRow
{
  private ?int $categoryId;
  private ?int $brandId;
  private int $nextDisplayOrder = 1;

  // Thứ tự cột trong file Excel (bắt đầu từ 0)
  // A:STT | B:Danh mục | C:Hãng | D:Tên Laptop | E:Mã SP | F:Năm SX | G:Màu | H:Mô tả ngắn
  // I:Thông tin SP | J:Mô tả chi tiết | K:GPU | L:Cổng kết nối | M:Hướng tới KH | N:Pin
  // O:Thiết kế | P:Giá bán | Q:Giá niêm yết | R:Giá nhập | S:Point | T:Số lượng | U:Ảnh
  private const COL_TEN_LAPTOP         = 3;
  private const COL_MA_SAN_PHAM        = 4;
  private const COL_NAM_SAN_XUAT       = 5;
  private const COL_MAU_MAY            = 6;
  private const COL_MO_TA_NGAN         = 7;
  private const COL_THONG_TIN_SP       = 8;
  private const COL_MO_TA_CHI_TIET     = 9;
  private const COL_GPU                = 10;
  private const COL_CONG_KET_NOI       = 11;
  private const COL_KHACH_HANG         = 12;
  private const COL_PIN                = 13;
  private const COL_THIET_KE           = 14;
  private const COL_GIA_BAN_YEN        = 15;
  private const COL_GIA_NIEM_YET_YEN   = 16;
  private const COL_GIA_NHAP_YEN       = 17;
  private const COL_SO_LUONG           = 19;


  public function __construct(?int $categoryId = null, ?int $brandId = null)
  {
    $this->categoryId = $categoryId;
    $this->brandId = $brandId;

    $query = $this->brandId
        ? Product::withTrashed()->where('brand_id', $this->brandId)
        : Product::withTrashed()->where('category_id', $this->categoryId);
    $this->nextDisplayOrder = ($query->max('display_order') ?? 0) + 1;
  }

  public function startRow(): int
  {
    return 2;
  }

  public function model(array $row)
  {
    $name = $this->val($row, self::COL_TEN_LAPTOP);
    $sku  = $this->val($row, self::COL_MA_SAN_PHAM);

    // Bỏ qua row nếu không có tên và mã sản phẩm
    if (!$name && !$sku) {
      return null;
    }

    // Nếu SKU đã tồn tại thì bỏ qua
    if ($sku && Product::where('sku', $sku)->exists()) {
      return null;
    }
    // Nếu không có SKU thì tự sinh
    if (!$sku) {
      $sku = 'PC-' . strtoupper(Str::random(8));
    }

    $priceJpy = $this->parsePrice($this->val($row, self::COL_GIA_BAN_YEN));
    $originalPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NIEM_YET_YEN));
    $costPriceJpy = $this->parsePrice($this->val($row, self::COL_GIA_NHAP_YEN));

    // Gom các thông tin riêng của laptop vào attributes
    $attributes = array_filter([
      'year' => $this->val($row, self::COL_NAM_SAN_XUAT),
      'color' => $this->val($row, self::COL_MAU_MAY),
      'gpu' => $this->val($row, self::COL_GPU),
      'ports' => $this->val($row, self::COL_CONG_KET_NOI),
      'target_customer' => $this->val($row, self::COL_KHACH_HANG),
      'battery' => $this->val($row, self::COL_PIN),
      'design' => $this->val($row, self::COL_THIET_KE),
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
