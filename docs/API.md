# SG Watch - API Documentation

> Base URL: `/api/v1`
> Auth Header: `Authorization: Bearer {token}`

---

## A. Guest APIs (No Authentication)

---

### 1. POST `/api/v1/login`

Login bằng email + password. Trả JWT token.

**Request:**

```json
{
  "email": "user@example.com",
  "password": "MyPassword123",
  "verification_code": "123456"
}
```

| Field | Type | Required | Note |
|-------|------|----------|------|
| email | string (email) | Yes* | Bắt buộc nếu không có user_id |
| password | string | Yes | |
| verification_code | string | No | Chỉ dùng cho admin 2FA |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "token": "eyJ0eXAiOiJKV1Qi..."
  }
}
```

**Response - Admin cần xác minh 2FA (200):**

```json
{
  "data": {
    "message": "認証コードをメールで送信しました",
    "requires_verification": true,
    "status_code": 200
  }
}
```

**Error (401):**

```json
{
  "message": "ユーザー名とパスワードが一致しません。",
  "status_code": 401
}
```

---

### 2. POST `/api/v1/register`

Đăng ký tài khoản mới.

**Request:**

```json
{
  "first_name": "Taro",
  "last_name": "Yamada",
  "email": "taro@example.com",
  "password": "SecurePass1",
  "password_confirmation": "SecurePass1",
  "invite_code": "ABC123"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| first_name | string | Yes | max: 50 |
| last_name | string | Yes | max: 50 |
| email | string (email) | Yes | unique, max: 255 |
| password | string | Yes | 8-16 chars |
| password_confirmation | string | Yes | must match password |
| invite_code | string | No | must exist in users table |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "登録が完了しました"
  }
}
```

> Nếu email verification enabled: message = `"確認メールを送信しました"`

**Error - Validation (422):**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["このメールアドレスは既に使用されています"],
    "password": ["パスワードは8文字以上16文字以下で入力してください"]
  }
}
```

---

### 3. POST `/api/v1/password/otp/send`

Quên mật khẩu trên App - Bước 1: Gửi OTP 6 số qua email.

**Request:**

```json
{
  "email": "user@example.com"
}
```

| Field | Type | Required |
|-------|------|----------|
| email | string (email) | Yes |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "パスワードリセット用のOTPコードを送信しました"
  }
}
```

---

### 4. POST `/api/v1/password/otp/verify`

Quên mật khẩu trên App - Bước 2: Xác minh OTP → nhận `reset_token`.

**Request:**

```json
{
  "email": "user@example.com",
  "otp": "352325"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| email | string (email) | Yes | |
| otp | string | Yes | đúng 6 ký tự |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "OTPコードが確認されました",
    "reset_token": "a1b2c3d4e5f6g7h8i9j0..."
  }
}
```

> Lưu `reset_token` để dùng cho bước 3.

**Error - Sai OTP (400):**

```json
{
  "message": "認証コードが無効です",
  "status_code": 400
}
```

**Error - Quá 5 lần (429):**

```json
{
  "message": "試行回数が多すぎます。新しいコードをリクエストしてください。",
  "status_code": 429
}
```

---

### 5. POST `/api/v1/password/reset`

Quên mật khẩu trên App - Bước 3: Đặt mật khẩu mới bằng `reset_token`.

**Request:**

```json
{
  "reset_token": "a1b2c3d4e5f6g7h8i9j0...",
  "password": "NewPass123",
  "password_confirmation": "NewPass123"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| reset_token | string | Yes | Từ bước 2 |
| password | string | Yes | 8-16 chars |
| password_confirmation | string | Yes | must match password |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "パスワードがリセットされました"
  }
}
```

---

### 6. POST `/api/v1/forgot-password`

Quên mật khẩu trên Web - Gửi link reset qua email.

**Request:**

```json
{
  "email": "user@example.com",
  "redirect_url": "https://mysite.com/reset-password"
}
```

| Field | Type | Required | Note |
|-------|------|----------|------|
| email | string (email) | Yes | |
| redirect_url | string | No | URL redirect sau khi click link trong email |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "パスワードリセットメールを送信しました"
  }
}
```

---

### 7. POST `/api/v1/reset-password/{token}`

Web reset mật khẩu bằng token từ email link.

**URL Params:** `token` - reset token từ email

**Request:**

```json
{
  "password": "NewPass123",
  "password_confirmation": "NewPass123"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| password | string | Yes | 8-16 chars |
| password_confirmation | string | Yes | must match password |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "パスワードがリセットされました"
  }
}
```

---

### 8. GET `/api/v1/verify-registration/{token}`

Xác minh email đăng ký (khi email verification enabled).

**URL Params:** `token` - verification token từ email

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "メールアドレスが確認されました"
  }
}
```

---

### 9. GET `/api/v1/check-reset-token/{token}`

Kiểm tra reset token còn hợp lệ không (Web dùng trước khi hiện form đổi MK).

**URL Params:** `token` - reset token

**Response - Valid (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "Token is valid"
  }
}
```

**Error - Invalid (404):**

```json
{
  "message": "Token is invalid or expired",
  "status_code": 404
}
```

---

## B. Member APIs (JWT Required)

> Header: `Authorization: Bearer {token}`

---

### 10. GET `/api/v1/user-info`

Lấy thông tin user đang đăng nhập.

**Request:** Không có body.

**Response - Success (200):**

```json
{
  "data": {
    "message": "Success",
    "status_code": 200,
    "data": {
      "user": {
        "id": "1",
        "first_name": "Taro",
        "last_name": "Yamada",
        "avatar_url": "https://example.com/storage/avatars/photo.jpg",
        "gender": "male",
        "birthday": "1990-01-15",
        "role": "user",
        "email": "taro@example.com"
      }
    }
  }
}
```

| Field | Type | Nullable | Note |
|-------|------|----------|------|
| id | string | No | |
| first_name | string | No | |
| last_name | string | No | |
| avatar_url | string | Yes | Full URL, null nếu chưa có |
| gender | string | Yes | `male`, `female`, `other`, `unknown` |
| birthday | string (Y-m-d) | Yes | |
| role | string | Yes | `admin` hoặc `user` |
| email | string | Yes | |

---

### 11. PUT `/api/v1/update-profile`

Cập nhật thông tin cá nhân. Gửi field nào update field đó.

**Request:**

```json
{
  "first_name": "Jiro",
  "last_name": "Tanaka",
  "gender": "male",
  "birthday": "1990-05-20",
  "avatar_url": "uploads/avatars/abc.jpg"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| first_name | string | No | max: 50 |
| last_name | string | No | max: 50 |
| gender | string | No | enum: `male`, `female`, `other`, `unknown` |
| birthday | string | No | format: `Y-m-d`, before today |
| avatar_url | string | No | max: 500 |

**Response - Success (200):**

```json
{
  "data": {
    "message": "Profile updated successfully",
    "status_code": 200,
    "data": {
      "user": {
        "id": "1",
        "first_name": "Jiro",
        "last_name": "Tanaka",
        "avatar_url": "https://example.com/storage/uploads/avatars/abc.jpg",
        "gender": "male",
        "birthday": "1990-05-20",
        "role": "user",
        "email": "taro@example.com"
      }
    }
  }
}
```

---

### 12. POST `/api/v1/update-avatar`

Upload avatar (multipart/form-data).

**Request:** `Content-Type: multipart/form-data`

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| avatar | file | Yes | jpeg, jpg, png, gif, webp. Max 100MB |

**Response - Success (200):**

```json
{
  "data": {
    "message": "Avatar updated successfully",
    "status_code": 200,
    "data": {
      "avatar_url": "https://example.com/storage/avatars/abc.jpg",
      "path": "avatars/abc.jpg"
    }
  }
}
```

---

### 13. DELETE `/api/v1/delete-avatar`

Xóa avatar.

**Request:** Không có body.

**Response - Success (200):**

```json
{
  "data": {
    "message": "Avatar deleted successfully",
    "status_code": 200,
    "data": {
      "success": true
    }
  }
}
```

---

### 14. POST `/api/v1/change-password`

Đổi mật khẩu (biết mật khẩu cũ).

**Request:**

```json
{
  "password_old": "OldPass123",
  "password": "NewPass456",
  "password_confirmation": "NewPass456"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| password_old | string | Yes | 8-16 chars |
| password | string | Yes | 8-16 chars |
| password_confirmation | string | Yes | must match password |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "パスワードが変更されました"
  }
}
```

---

### 15. POST `/api/v1/request-email-change`

Yêu cầu đổi email - Bước 1: Gửi link xác nhận đến email mới.

**Request:**

```json
{
  "new_email": "new@example.com",
  "password": "MyPassword123"
}
```

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| new_email | string (email) | Yes | unique, max: 255 |
| password | string | Yes | Xác nhận danh tính |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "確認メールを送信しました"
  }
}
```

---

### 16. POST `/api/v1/confirm-email-change`

Xác nhận đổi email - Bước 2: Dùng token từ email.

**Request:**

```json
{
  "token": "abc123def456..."
}
```

| Field | Type | Required |
|-------|------|----------|
| token | string | Yes |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "メールアドレスが変更されました"
  }
}
```

---

### 17. GET `/api/v1/pending-email-change`

Kiểm tra có yêu cầu đổi email đang chờ xác nhận không.

**Request:** Không có body.

**Response - Có pending (200):**

```json
{
  "data": {
    "status_code": 200,
    "data": {
      "pending": true,
      "new_email": "new@example.com"
    }
  }
}
```

**Response - Không có pending (200):**

```json
{
  "data": {
    "status_code": 200,
    "data": {
      "pending": false
    }
  }
}
```

---

### 18. GET `/api/v1/logout`

Đăng xuất, vô hiệu hóa JWT token.

**Request:** Không có body.

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "ログアウトしました"
  }
}
```

---

### 19. DELETE `/api/v1/withdraw`

Xóa tài khoản (soft delete).

**Request:** Không có body.

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "退会しました"
  }
}
```

---

## C. Address APIs (JWT Required)

> Header: `Authorization: Bearer {token}`

---

### 20. GET `/api/v1/addresses`

Lấy danh sách địa chỉ của user.

**Request:** Không có body.

**Response - Success (200):**

```json
{
  "data": {
    "message": "Success",
    "status_code": 200,
    "data": {
      "addresses": [
        {
          "id": 1,
          "label": "自宅",
          "country_code": "JP",
          "input_mode": "manual",
          "postal_code": "150-0001",
          "phone": "090-1234-5678",
          "image_url": null,
          "is_default": true,
          "created_at": "2026-02-20 10:00:00",
          "jp_detail": {
            "prefecture": "東京都",
            "city": "渋谷区",
            "ward_town": "神宮前",
            "banchi": "1-2-3",
            "building_name": "SGマンション",
            "room_no": "101"
          }
        },
        {
          "id": 2,
          "label": "Nhà VN",
          "country_code": "VN",
          "input_mode": "manual",
          "postal_code": "700000",
          "phone": "0901234567",
          "image_url": null,
          "is_default": false,
          "created_at": "2026-02-20 11:00:00",
          "vn_detail": {
            "province_city": "TP. Hồ Chí Minh",
            "district": "Quận 1",
            "ward_commune": "Phường Bến Nghé",
            "detail_address": "123 Nguyễn Huệ",
            "building_name": null,
            "room_no": null
          }
        }
      ]
    }
  }
}
```

**Address object fields:**

| Field | Type | Nullable | Note |
|-------|------|----------|------|
| id | int | No | |
| label | string | No | Tên địa chỉ (VD: "自宅", "会社") |
| country_code | string | No | `JP` hoặc `VN` |
| input_mode | string | No | `manual` hoặc `image_only` |
| postal_code | string | Yes | Mã bưu điện |
| phone | string | Yes | Số điện thoại |
| image_url | string | Yes | Full URL ảnh (JP luôn bắt buộc, VN nullable) |
| is_default | bool | No | Địa chỉ mặc định |
| created_at | string | No | Format: `Y-m-d H:i:s` |
| jp_detail | object | Yes | Chỉ có khi country_code = JP |
| vn_detail | object | Yes | Chỉ có khi country_code = VN |

**jp_detail object:**

| Field | Type | Nullable |
|-------|------|----------|
| prefecture | string | No |
| city | string | No |
| ward_town | string | No |
| banchi | string | No |
| building_name | string | Yes |
| room_no | string | Yes |

**vn_detail object:**

| Field | Type | Nullable |
|-------|------|----------|
| province_city | string | No |
| district | string | No |
| ward_commune | string | No |
| detail_address | string | No |
| building_name | string | Yes |
| room_no | string | Yes |

---

### 21. POST `/api/v1/addresses`

Tạo địa chỉ mới.

**Request - JP (manual):**

```json
{
  "label": "自宅",
  "country_code": "JP",
  "input_mode": "manual",
  "postal_code": "150-0001",
  "phone": "090-1234-5678",
  "image_url": "uploads/addresses/kanji_address.jpg",
  "is_default": true,
  "jp_detail": {
    "prefecture": "東京都",
    "city": "渋谷区",
    "ward_town": "神宮前",
    "banchi": "1-2-3",
    "building_name": "SGマンション",
    "room_no": "101"
  }
}
```

**Request - JP (image_only):**

```json
{
  "label": "会社",
  "country_code": "JP",
  "input_mode": "image_only",
  "image_url": "uploads/addresses/photo.jpg"
}
```

**Request - VN (manual):**

```json
{
  "label": "Nhà",
  "country_code": "VN",
  "input_mode": "manual",
  "postal_code": "700000",
  "phone": "0901234567",
  "vn_detail": {
    "province_city": "TP. Hồ Chí Minh",
    "district": "Quận 1",
    "ward_commune": "Phường Bến Nghé",
    "detail_address": "123 Nguyễn Huệ",
    "building_name": "Tòa ABC",
    "room_no": "501"
  }
}
```

**Validation rules:**

| Field | JP manual | JP image_only | VN |
|-------|-----------|---------------|-----|
| label | required, max:100 | required, max:100 | required, max:100 |
| country_code | required, `JP` | required, `JP` | required, `VN` |
| input_mode | required, `manual` | required, `image_only` | required, `manual` |
| postal_code | required, max:20 | nullable | required, max:20 |
| phone | required, max:30 | nullable | required, max:30 |
| image_url | **required**, max:500 | **required**, max:500 | nullable |
| is_default | optional, boolean | optional, boolean | optional, boolean |
| jp_detail.prefecture | required, max:50 | - | - |
| jp_detail.city | required, max:100 | - | - |
| jp_detail.ward_town | required, max:100 | - | - |
| jp_detail.banchi | required, max:100 | - | - |
| jp_detail.building_name | nullable, max:150 | - | - |
| jp_detail.room_no | nullable, max:50 | - | - |
| vn_detail.province_city | - | - | required, max:100 |
| vn_detail.district | - | - | required, max:100 |
| vn_detail.ward_commune | - | - | required, max:100 |
| vn_detail.detail_address | - | - | required, max:255 |
| vn_detail.building_name | - | - | nullable, max:150 |
| vn_detail.room_no | - | - | nullable, max:50 |

**Response - Success (200):**

> Trả lại **toàn bộ danh sách** addresses (cùng format như GET `/api/v1/addresses`)

```json
{
  "data": {
    "message": "Success",
    "status_code": 200,
    "data": {
      "addresses": [ ... ]
    }
  }
}
```

---

### 22. PUT `/api/v1/addresses/{id}`

Cập nhật địa chỉ. Gửi field nào update field đó. **Không** cho đổi `country_code`.

**URL Params:** `id` - address ID

**Request:**

```json
{
  "label": "新しい自宅",
  "phone": "080-9999-8888",
  "is_default": true,
  "jp_detail": {
    "city": "新宿区"
  }
}
```

> Tất cả fields giống POST nhưng đều là `optional` (sometimes).
> Nếu gửi `jp_detail` hoặc `vn_detail` kèm `country_code` + `input_mode`, validation conditional sẽ áp dụng tương tự POST.

**Response - Success (200):**

> Trả lại **toàn bộ danh sách** addresses.

```json
{
  "data": {
    "message": "Success",
    "status_code": 200,
    "data": {
      "addresses": [ ... ]
    }
  }
}
```

---

### 23. DELETE `/api/v1/addresses/{id}`

Xóa địa chỉ.

**URL Params:** `id` - address ID

**Request:** Không có body.

**Response - Success (200):**

> Trả lại **toàn bộ danh sách** addresses còn lại.

```json
{
  "data": {
    "message": "Success",
    "status_code": 200,
    "data": {
      "addresses": [ ... ]
    }
  }
}
```

---

## D. FCM Token APIs (JWT Required)

---

### 24. POST `/api/v1/fcm_token`

Đăng ký FCM token cho push notification.

**Request:**

```json
{
  "fcm_token": "dGhpcyBpcyBhIHRlc3QgdG9rZW4..."
}
```

| Field | Type | Required |
|-------|------|----------|
| fcm_token | string | Yes |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "FCM token registered"
  }
}
```

---

### 25. DELETE `/api/v1/fcm_token`

Xóa FCM token (khi logout hoặc unregister push).

**Request:**

```json
{
  "fcm_token": "dGhpcyBpcyBhIHRlc3QgdG9rZW4..."
}
```

| Field | Type | Required |
|-------|------|----------|
| fcm_token | string | Yes |

**Response - Success (200):**

```json
{
  "data": {
    "status_code": 200,
    "message": "FCM token deleted"
  }
}
```

---

## E. Error Response Format

Tất cả API trả error theo format chung:

**Validation Error (422):**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message 1",
      "Error message 2"
    ]
  }
}
```

**Unauthorized (401):**

```json
{
  "message": "Unauthenticated.",
  "status_code": 401
}
```

**Not Found (404):**

```json
{
  "message": "Resource not found",
  "status_code": 404
}
```

**Server Error (500):**

```json
{
  "message": "Internal server error",
  "status_code": 500
}
```

---

## F. Tổng hợp

| # | Method | URL | Auth | Mục đích |
|---|--------|-----|------|----------|
| 1 | POST | `/api/v1/login` | No | Đăng nhập |
| 2 | POST | `/api/v1/register` | No | Đăng ký |
| 3 | POST | `/api/v1/password/otp/send` | No | Quên MK (App) - Gửi OTP |
| 4 | POST | `/api/v1/password/otp/verify` | No | Quên MK (App) - Xác minh OTP |
| 5 | POST | `/api/v1/password/reset` | No | Quên MK (App) - Đặt MK mới |
| 6 | POST | `/api/v1/forgot-password` | No | Quên MK (Web) - Gửi link email |
| 7 | POST | `/api/v1/reset-password/{token}` | No | Quên MK (Web) - Đặt MK mới |
| 8 | GET | `/api/v1/verify-registration/{token}` | No | Xác minh email đăng ký |
| 9 | GET | `/api/v1/check-reset-token/{token}` | No | Kiểm tra reset token |
| 10 | GET | `/api/v1/user-info` | JWT | Lấy thông tin user |
| 11 | PUT | `/api/v1/update-profile` | JWT | Cập nhật profile |
| 12 | POST | `/api/v1/update-avatar` | JWT | Upload avatar |
| 13 | DELETE | `/api/v1/delete-avatar` | JWT | Xóa avatar |
| 14 | POST | `/api/v1/change-password` | JWT | Đổi mật khẩu |
| 15 | POST | `/api/v1/request-email-change` | JWT | Đổi email - Gửi xác nhận |
| 16 | POST | `/api/v1/confirm-email-change` | JWT | Đổi email - Xác nhận |
| 17 | GET | `/api/v1/pending-email-change` | JWT | Kiểm tra pending email change |
| 18 | GET | `/api/v1/logout` | JWT | Đăng xuất |
| 19 | DELETE | `/api/v1/withdraw` | JWT | Xóa tài khoản |
| 20 | GET | `/api/v1/addresses` | JWT | Lấy danh sách địa chỉ |
| 21 | POST | `/api/v1/addresses` | JWT | Tạo địa chỉ |
| 22 | PUT | `/api/v1/addresses/{id}` | JWT | Cập nhật địa chỉ |
| 23 | DELETE | `/api/v1/addresses/{id}` | JWT | Xóa địa chỉ |
| 24 | POST | `/api/v1/fcm_token` | JWT | Đăng ký FCM token |
| 25 | DELETE | `/api/v1/fcm_token` | JWT | Xóa FCM token |
