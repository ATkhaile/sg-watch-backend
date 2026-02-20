# User Addresses - Database Design

> Mỗi user có nhiều địa chỉ. Mỗi địa chỉ thuộc 1 trong 2 vùng: Nhật Bản (JP) hoặc Việt Nam (VN).

---

## ERD

```
users
  │
  │ 1:N
  ▼
user_addresses (master)
  │
  ├── 1:1 ──► user_address_jp   (country_code = JP)
  │
  └── 1:1 ──► user_address_vn   (country_code = VN)
```

---

## Tables

### 1. `user_addresses` (master - chung cho cả JP & VN)

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint PK | | auto | |
| `user_id` | FK → users.id | | | cascade delete |
| `label` | varchar(100) | | | Tên gợi nhớ: "Nhà", "Công ty", "自宅", "会社" |
| `country_code` | ENUM(JP, VN) | | | |
| `input_mode` | ENUM(manual, image_only) | | manual | JP dùng cả 2 mode; VN chỉ manual |
| `postal_code` | varchar(20) | yes | null | Mã bưu điện (JP/VN đều có) |
| `phone` | varchar(30) | yes | null | Số điện thoại |
| `image_url` | varchar(500) | yes | null | 1 ảnh duy nhất / địa chỉ |
| `is_default` | boolean | | false | Địa chỉ mặc định |
| `created_at` | timestamp | | | |
| `updated_at` | timestamp | | | |

**Constraints:**
- `UNIQUE (user_id, label)` — mỗi user không trùng tên địa chỉ
- `INDEX (user_id, country_code)`
- `INDEX (user_id, is_default)`

---

### 2. `user_address_jp` (detail Nhật Bản)

| Column | Type | Nullable | Description |
|---|---|---|---|
| `address_id` | PK + FK → user_addresses.id | | cascade delete |
| `prefecture` | varchar(50) | yes | 都道府県 (tỉnh) |
| `city` | varchar(100) | yes | 市区町村 (thành phố) |
| `ward_town` | varchar(100) | yes | 町域 (quận/huyện/thị trấn) |
| `banchi` | varchar(100) | yes | 番地 (số banchi) |
| `room_no` | varchar(50) | yes | 部屋番号 (số phòng) |
| `created_at` | timestamp | | |
| `updated_at` | timestamp | | |

> Tất cả nullable vì mode `image_only` không cần nhập.

---

### 3. `user_address_vn` (detail Việt Nam)

| Column | Type | Nullable | Description |
|---|---|---|---|
| `address_id` | PK + FK → user_addresses.id | | cascade delete |
| `province_city` | varchar(100) | no | Tỉnh / Thành phố |
| `district` | varchar(100) | no | Quận / Huyện / Thị trấn |
| `ward_commune` | varchar(100) | no | Xã / Phường |
| `detail_address` | varchar(255) | no | Địa chỉ chi tiết |
| `building_name` | varchar(150) | yes | Tên tòa nhà |
| `room_no` | varchar(50) | yes | Số phòng |
| `created_at` | timestamp | | |
| `updated_at` | timestamp | | |

---

## Input Mode & Validation Rules

### JP + `image_only` (chỉ upload ảnh có ghi địa chỉ)

| Field | Required |
|---|---|
| `image_url` | **bắt buộc** |
| `postal_code` | optional |
| `phone` | optional |
| JP detail fields | optional (có thể để trống hết) |

### JP + `manual` (nhập tay, có thể kèm ảnh)

| Field | Required |
|---|---|
| `postal_code` | **bắt buộc** |
| `phone` | **bắt buộc** |
| `prefecture` | **bắt buộc** |
| `city` | **bắt buộc** |
| `ward_town` | **bắt buộc** |
| `banchi` | **bắt buộc** |
| `room_no` | optional |
| `image_url` | optional |

### VN + `manual`

| Field | Required |
|---|---|
| `postal_code` | **bắt buộc** |
| `phone` | **bắt buộc** |
| `province_city` | **bắt buộc** |
| `district` | **bắt buộc** |
| `ward_commune` | **bắt buộc** |
| `detail_address` | **bắt buộc** |
| `building_name` | optional |
| `room_no` | optional |
| `image_url` | optional |

---

## Models & Relationships

```
User
  ├── addresses()        → HasMany(UserAddress)
  └── defaultAddress()   → HasOne(UserAddress) where is_default=true

UserAddress
  ├── user()             → BelongsTo(User)
  ├── jpDetail()         → HasOne(UserAddressJp)
  ├── vnDetail()         → HasOne(UserAddressVn)
  └── $address->detail   → accessor: tự động lấy JP hoặc VN theo country_code

UserAddressJp
  └── address()          → BelongsTo(UserAddress)

UserAddressVn
  └── address()          → BelongsTo(UserAddress)
```

---

## Files

| File | Description |
|---|---|
| `database/migrations/2026_02_20_000001_create_user_addresses_table.php` | Migration master |
| `database/migrations/2026_02_20_000002_create_user_address_jp_table.php` | Migration JP detail |
| `database/migrations/2026_02_20_000003_create_user_address_vn_table.php` | Migration VN detail |
| `app/Models/UserAddress.php` | Model master |
| `app/Models/UserAddressJp.php` | Model JP |
| `app/Models/UserAddressVn.php` | Model VN |
| `app/Models/User.php` | Thêm `addresses()`, `defaultAddress()` |

---

## Example Data

### JP image_only (chỉ upload ảnh)

```json
{
    "label": "自宅",
    "country_code": "JP",
    "input_mode": "image_only",
    "image_url": "addresses/1/home.jpg",
    "jp_detail": {}
}
```

### JP manual + ảnh

```json
{
    "label": "会社",
    "country_code": "JP",
    "input_mode": "manual",
    "postal_code": "150-0001",
    "phone": "03-1234-5678",
    "image_url": "addresses/1/office.jpg",
    "jp_detail": {
        "prefecture": "東京都",
        "city": "渋谷区",
        "ward_town": "神宮前",
        "banchi": "1-2-3",
        "room_no": "301"
    }
}
```

### VN manual

```json
{
    "label": "Nhà",
    "country_code": "VN",
    "input_mode": "manual",
    "postal_code": "700000",
    "phone": "0901234567",
    "image_url": null,
    "vn_detail": {
        "province_city": "TP. Hồ Chí Minh",
        "district": "Quận 1",
        "ward_commune": "Phường Bến Nghé",
        "detail_address": "123 Nguyễn Huệ",
        "building_name": "Bitexco Tower",
        "room_no": "1501"
    }
}
```
