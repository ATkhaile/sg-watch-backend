# TDBS API テストレポート

## 概要
TDBS Domain移行後のAPIテスト結果をまとめたレポート

## テスト環境
- テストツール: API Tester CLI (アプリケーションリポジトリ：`app/` ディレクトリ)
- `/app/docs/API_TESTER_CLI_GUIDE.md`を使用する
- ベースURL: http://api.elc-dev.local/api/v1
- 最終テスト: 2026-01-29

## テストアカウント
| ロール | Email | Password |
|--------|-------|----------|
| Super Admin | account+init@gameagelayer.com | Laravel@2025 |
| Member 1 | member+test1@gameagelayer.com | Laravel@2025 |

---

## 結果サマリー

| カテゴリ | 成功 | 失敗 | 合計 |
|---------|------|------|------|
| Admin GET API | 30 | 0 | 30 |
| Admin PUT/POST/DELETE API | 19 | 0 | 19 |
| User GET API | 18 | 0 | 18 |
| User POST/PUT API | 10 | 0 | 10 |
| **合計** | **77** | **0** | **77** |

**成功率: 100%**

---

## 成功したAPI (77件)

### Admin GET APIs (30/30)
- [x] GET /tdbs/admin/member
- [x] GET /tdbs/admin/member/{id}
- [x] GET /tdbs/admin/member/{id}/reservations
- [x] GET /tdbs/admin/member/{id}/reservations_use
- [x] GET /tdbs/admin/member/{id}/plan
- [x] GET /tdbs/admin/news
- [x] GET /tdbs/admin/news/{id}
- [x] GET /tdbs/admin/shop
- [x] GET /tdbs/admin/shop/{id}
- [x] GET /tdbs/admin/{location_id}/golph/reservation
- [x] GET /tdbs/admin/{location_id}/golph/reservation/{reservation_number}
- [x] GET /tdbs/admin/{location_id}/golph/coupon
- [x] GET /tdbs/admin/{location_id}/golph/coupon/{id}
- [x] GET /tdbs/admin/{location_id}/golph/plan
- [x] GET /tdbs/admin/{location_id}/golph/plan/{id}
- [x] GET /tdbs/admin/{location_id}/golph/schedule
- [x] GET /tdbs/admin/{location_id}/golph/schedule/detail
- [x] GET /tdbs/admin/{location_id}/golph/schedule/template
- [x] GET /tdbs/admin/{location_id}/sauna/reservation
- [x] GET /tdbs/admin/{location_id}/sauna/reservation/{date}
- [x] GET /tdbs/admin/{location_id}/sauna/coupon
- [x] GET /tdbs/admin/{location_id}/sauna/coupon/{id}
- [x] GET /tdbs/admin/{location_id}/sauna/plan
- [x] GET /tdbs/admin/{location_id}/sauna/plan/{id}
- [x] GET /tdbs/admin/{location_id}/sauna/schedule
- [x] GET /tdbs/admin/{location_id}/sauna/schedule/detail
- [x] GET /tdbs/admin/{location_id}/sauna/schedule/template
- [x] GET /tdbs/admin/{location_id}/sauna/schedule/options
- [x] GET /tdbs/admin/{location_id}/sauna/schedule/months
- [x] GET /tdbs/admin/{location_id}/sauna/schedule/options/{date}

### Admin PUT/POST/DELETE APIs (19/19)
- [x] PUT /tdbs/admin/member/{id}
- [x] PUT /tdbs/admin/member/{id}/plan
- [x] POST /tdbs/admin/news
- [x] PUT /tdbs/admin/news/{id}
- [x] DELETE /tdbs/admin/news/{id}
- [x] POST /tdbs/admin/{location_id}/golph/coupon
- [x] PUT /tdbs/admin/{location_id}/golph/coupon/{id}
- [x] DELETE /tdbs/admin/{location_id}/golph/coupon/{id}
- [x] POST /tdbs/admin/{location_id}/golph/plan
- [x] PUT /tdbs/admin/{location_id}/golph/plan/{id}
- [x] DELETE /tdbs/admin/{location_id}/golph/plan/{id}
- [x] PUT /tdbs/admin/{location_id}/golph/schedule
- [x] PUT /tdbs/admin/{location_id}/golph/shop
- [x] POST /tdbs/admin/{location_id}/sauna/coupon
- [x] PUT /tdbs/admin/{location_id}/sauna/coupon/{id}
- [x] DELETE /tdbs/admin/{location_id}/sauna/coupon/{id}
- [x] PUT /tdbs/admin/{location_id}/sauna/schedule
- [x] PUT /tdbs/admin/{location_id}/sauna/shop

### User GET APIs (18/18)
- [x] GET /tdbs/user/profile
- [x] GET /tdbs/user/card
- [x] GET /tdbs/user/plan
- [x] GET /tdbs/user/reservation/history
- [x] GET /tdbs/user/reservation/confirm
- [x] GET /tdbs/user/{location_id}/golph/shop
- [x] GET /tdbs/user/{location_id}/golph/schedule
- [x] GET /tdbs/user/{location_id}/golph/schedule/{date}
- [x] GET /tdbs/user/{location_id}/golph/coupon
- [x] GET /tdbs/user/{location_id}/golph/reservation/confirm
- [x] GET /tdbs/user/{location_id}/sauna/shop
- [x] GET /tdbs/user/{location_id}/sauna/schedule
- [x] GET /tdbs/user/{location_id}/sauna/schedule/{date}
- [x] GET /tdbs/user/{location_id}/sauna/coupon
- [x] GET /tdbs/user/{location_id}/sauna/reservation/confirm
- [x] GET /tdbs/user/news
- [x] GET /tdbs/user/news/{id}
- [x] GET /tdbs/user/prefecture

### User POST/PUT APIs (10/10)
- [x] PUT /tdbs/user/profile
- [x] POST /tdbs/user/contact
- [x] POST /tdbs/user/card
- [x] DELETE /tdbs/user/card/{id}
- [x] POST /tdbs/user/{location_id}/golph/plan
- [x] POST /tdbs/user/{location_id}/golph/plan/change-plan
- [x] POST /tdbs/user/{location_id}/golph/reservation/payment
- [x] POST /tdbs/user/{location_id}/sauna/reservation/payment
- [x] GET /tdbs/user/{location_id}/golph/reservation/cancel/{id}
- [x] GET /tdbs/user/{location_id}/sauna/reservation/cancel/{id}

## 参考：テスト実行コマンド

```bash
# Admin認証
npm run api-tester -- auth account+init@gameagelayer.com Laravel@2025

# Member認証
npm run api-tester -- auth member+test1@gameagelayer.com Laravel@2025

# APIテスト例
npm run api-tester -- req GET tdbs/admin/member
npm run api-tester -- req PUT tdbs/admin/member/5 '{"...json..."}'

# PUT golph plan (全フィールド必須)
npm run api-tester -- req PUT tdbs/admin/1/golph/plan/2 '{"name":"プラン名","status":"active","price":8000,"highline_display":"","available_reservation":["weekdays","weekends_holidays"],"start_time":"09:00","end_time":"21:00","available_from_type":"reservation_slots","available_from_value":1,"available_from_unit":"minus","available_to_type":"reservation_slots","available_to_value":30,"available_to_unit":"hour","accompanying_slots":1,"no_limit":false,"limit":4,"charge_people_price":3300,"charge_time_price_1":3300,"charge_time_price_2":4400,"charge_time_price_3":5500}'

# PUT golph schedule
npm run api-tester -- req PUT tdbs/admin/1/golph/schedule '{"date":"2026-02-15","time":10,"status":2,"schedule_type":1,"options":[{"name":"オプション1","price":1000,"type":1,"user_type":1,"unit":1,"is_active":1}]}'

# PUT golph shop (images.*.path_oldで既存画像維持)
npm run api-tester -- req PUT tdbs/admin/1/golph/shop '{"name":"店舗名","description":"説明","parking_flag":1,"parking_price":0,...,"images":[{"path_old":"http://..."}],"options":[{...}]}'
```

---

## 備考

- **ビジネスロジック上の制限**: 一部のAPIは特定の条件下でのみ成功する（例: 無料プランのみ購入可能、契約中プランがある場合のみ変更可能）
- **Stripe連携**: 予約支払い系APIはStripe決済フローが必要
- **ファイルアップロード**: /golph/schedule/import等はファイルアップロードが必要
