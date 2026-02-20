# TDBS モデル統合計画

**作成日:** 2026-01-25
**目的:** TDBSモデルを上位階層（共通モデル）へ移動する際の名称重複・役割重複の整理

---

## 1. 名称重複モデル一覧

以下のモデルは共通モデルとTDBSモデルで**同一名称**が存在する。

| TDBSモデル | 共通モデル | 重複度 |
|-----------|-----------|--------|
| `Tdbs\News` | `News` | **名称完全一致** |
| `Tdbs\PaymentPlan` | `PaymentPlan` | **名称完全一致** |
| `Tdbs\Prefecture` | `Prefecture` | **名称完全一致** |
| `Tdbs\SystemSetting` | `SystemSetting` | **名称完全一致** |
| `Tdbs\Schedule` | `Schedule` | **名称完全一致** |
| `Tdbs\Plan` | `Plan` | **名称完全一致** |
| `Tdbs\UserPlan` | `UserPurchasedPlan` | **役割類似** |

---

## 2. 各重複モデルの詳細比較

### 2.1 News（お知らせ）

| 項目 | Tdbs\News | News（共通） |
|------|-----------|-------------|
| **テーブル** | `tdbs_news` | `news` |
| **主キー** | `id`（integer） | `id`（integer） |
| **主要フィールド** | title, description, publish_date, publish_flag, creator_id | title, content, short_description, status, category_id, thumbnail, is_important, is_new, send_notification, published_at |
| **リレーション** | なし | Category, Tags, Creator(User) |
| **用途** | TDBS施設向けシンプルなお知らせ | 汎用CMS的なニュース機能（カテゴリ・タグ・通知付き） |

**統合判定:** ❌ **別モデル維持推奨**
- 共通Newsは多機能CMS（カテゴリ・タグ・通知・重要フラグ）
- TDBS Newsはシンプルな公開/非公開のみ
- 統合するとTDBS側に不要な複雑性が入る

**移行案:**
- TDBS側を `FacilityNews` や `TenantNews` にリネーム
- または `news` テーブルに `tenant_id` カラム追加でマルチテナント化

---

### 2.2 PaymentPlan（決済プラン）

| 項目 | Tdbs\PaymentPlan | PaymentPlan（共通） |
|------|------------------|---------------------|
| **テーブル** | `tdbs_payment_plans` | `payment_plans` |
| **主キー** | `id`（integer） | `payment_plan_id`（ULID） |
| **主要フィールド** | account_id, plan_id, card_id, payment_intent_id, total_amount, payment_status, coupon_id, discount_amount | user_id, stripe_account_id, name, description, price, payment_plan_type, stripe_plan_id, stripe_price_id, stripe_payment_link, cancel_hours |
| **用途** | プラン購入の**決済記録**（トランザクション） | 販売可能な**決済プラン定義**（マスター） |

**統合判定:** ❌ **役割が異なる（別概念）**
- 共通PaymentPlanは「売り物の定義」（マスターデータ）
- TDBS PaymentPlanは「購入履歴」（トランザクションデータ）

**移行案:**
- TDBS側を `PlanPayment` や `PlanPurchaseRecord` にリネーム
- 概念的には共通の `UserPurchasedPlan` に近い

---

### 2.3 Prefecture（都道府県）

| 項目 | Tdbs\Prefecture | Prefecture（共通） |
|------|-----------------|-------------------|
| **テーブル** | `tdbs_prefectures` | `prefectures` |
| **主キー** | `id`（integer） | `prefecture_id`（ULID） |
| **主要フィールド** | name, order_num | name, order_num |
| **リレーション** | なし | users |

**統合判定:** ✅ **統合可能（完全重複）**
- 同一の都道府県マスターデータ
- TDBSが独自テーブルを持つ必要はない

**移行案:**
- TDBS側を廃止し、共通Prefectureを参照
- `tdbs_accounts.prefecture_id` → `prefectures.prefecture_id` への外部キー変更
- 主キー型の違い（integer vs ULID）に注意

---

### 2.4 SystemSetting（システム設定）

| 項目 | Tdbs\SystemSetting | SystemSetting（共通） |
|------|-------------------|----------------------|
| **テーブル** | `tdbs_system_settings` | `system_settings` |
| **主キー** | `id`（integer） | `id`（integer） |
| **主要フィールド** | shop_id, type, content | key, value |
| **構造** | shop_id + type でユニーク（店舗別設定） | key-value形式（グローバル設定） |

**統合判定:** ⚠️ **条件付き統合可能**
- 共通SystemSettingはシンプルなkey-value
- TDBS SystemSettingは店舗（shop_id）単位の設定

**移行案:**
- 共通SystemSettingに `entity_type`, `entity_id` カラムを追加
- `entity_type='shop', entity_id=1` で店舗別設定を実現
- または TDBS側を `TenantSetting` にリネーム

---

### 2.5 Schedule（スケジュール）

| 項目 | Tdbs\Schedule | Schedule（共通） |
|------|--------------|-----------------|
| **テーブル** | `tdbs_schedules` | `schedules` |
| **主キー** | `id`（integer） | `id`（integer） |
| **主要フィールド** | shop_id, date, start_time, end_time, status, schedule_type, 各種料金, 各種オプションフラグ | title, body, start_time, end_time, create_user_id, category_id |
| **リレーション** | Options | Tags, Places, Users, Services, Persons, CustomValues, ScheduleLog |
| **用途** | **予約枠**（施設の空き状況・料金） | **カレンダーイベント**（ユーザーの予定） |

**統合判定:** ❌ **完全に別概念**
- 共通Scheduleはカレンダー/スケジューラー機能
- TDBS Scheduleは予約システムの「空き枠」定義

**移行案:**
- TDBS側を `ReservationSlot`, `AvailabilitySlot`, `BookingSlot` にリネーム
- 予約システム固有のモデルとして分離維持

---

### 2.6 Plan（プラン）

| 項目 | Tdbs\Plan | Plan（共通） |
|------|----------|-------------|
| **テーブル** | `tdbs_plans` | `plans` |
| **主キー** | `id`（integer） | `plan_id`（ULID） |
| **主要フィールド** | shop_id, name, code, status, price, 時間設定, 予約ルール, 定員設定, 料金体系 | name, icon_url, plan_text, description, payment_trigger_id, is_active, for_guest, category_id, tags, display_order |
| **リレーション** | Shop | Users, PaymentTrigger, Category, Memberships, RequiredMemberships |
| **用途** | **施設サービスプラン**（ゴルフ/サウナの利用プラン） | **サブスクリプションプラン**（会員権・課金プラン） |

**統合判定:** ❌ **概念が異なる**
- 共通Planはサブスクリプション/会員権の定義
- TDBS Planは施設利用サービスの定義（時間枠・定員・複雑な料金体系）

**移行案:**
- TDBS側を `ServicePlan`, `FacilityPlan`, `BookingPlan` にリネーム
- または共通側を `SubscriptionPlan`, `MembershipPlan` にリネーム

---

### 2.7 UserPlan vs UserPurchasedPlan（ユーザープラン）

| 項目 | Tdbs\UserPlan | UserPurchasedPlan（共通） |
|------|--------------|-------------------------|
| **テーブル** | `tdbs_user_plans` | `user_purchased_plans` |
| **主要フィールド** | shop_id, account_id, plan_id, subscription_id, payment_at, expire_end, count_remaining, payment_status | user_id, plan_id, membership_action, purchased_at, expires_at, metadata, granted_by |
| **用途** | TDBS施設のサブスクリプション状態（残回数・次月プラン変更） | プラン購入履歴（メンバーシップ付与アクション） |

**統合判定:** ⚠️ **役割類似だが詳細が異なる**
- 共通側はシンプルな購入履歴+メンバーシップ連携
- TDBS側は残回数・次月プラン変更・カード変更など複雑な状態管理

**移行案:**
- 共通モデルの拡張または継承パターンで対応
- TDBS固有の属性は `metadata` JSON に格納
- または別モデルとして `FacilitySubscription` に維持

---

## 3. 役割重複モデル一覧

名称は異なるが役割が重複する可能性のあるモデル。

| TDBSモデル | 共通モデル候補 | 重複度 |
|-----------|--------------|--------|
| `Tdbs\Account` | `User` | **役割重複（ユーザー管理）** |
| `Tdbs\Card` | `UserStripeCustomer` | **役割類似（決済情報）** |
| `Tdbs\PaymentHistory` | なし | TDBS固有 |
| `Tdbs\Reservation` | なし | TDBS固有 |
| `Tdbs\Coupon` | なし | TDBS固有 |
| `Tdbs\Shop` | なし | TDBS固有 |
| `Tdbs\Option` | なし | TDBS固有 |

---

### 3.1 Account vs User（ユーザー管理）

| 項目 | Tdbs\Account | User（共通） |
|------|-------------|-------------|
| **テーブル** | `tdbs_accounts` | `users` |
| **認証** | password, LINE連携（line_id, line_user_id, line_access_token） | password, JWT, Membership, Permission, Role |
| **個人情報** | 氏名（漢字/カナ）, 住所, 電話, 生年月日, グループ名 | name, email, avatar, description, company_name, phone_number, 住所 |
| **状態管理** | is_admin, account_type, verified, is_black, payment_status | status, is_system_admin, private_mode, is_online |
| **リレーション** | UserPlan | Roles, Permissions, Memberships, Entitlements, Follows |

**統合判定:** ⚠️ **統合検討が必要**
- 共通UserはRBAC（ロール・パーミッション）に対応
- TDBS AccountはLINE連携・ブラックリスト等の施設固有機能
- 同一人物が両方に存在する可能性あり（認証統合の課題）

**移行案:**
1. **完全統合**: User拡張（LINE連携カラム追加）+ `user_profiles` テーブルでテナント別属性
2. **リンクテーブル**: `User` ⇔ `TenantAccount` で多対多関係
3. **並行維持**: TDBS Accountを `FacilityMember` としてUser参照を持つ構造

---

### 3.2 Card vs UserStripeCustomer（決済情報）

| 項目 | Tdbs\Card | UserStripeCustomer（共通） |
|------|----------|---------------------------|
| **テーブル** | `tdbs_cards` | `user_stripe_customers` |
| **主要フィールド** | account_id, card_id, card_holder_name, brand, last4, exp_month, exp_year, status, default_flag | user_id, payment_plan_id, stripe_customer_id, stripe_price_id, stripe_account_identifier |
| **用途** | カード情報の詳細保存（複数カード対応） | Stripeカスタマー紐付け |

**統合判定:** ⚠️ **補完関係にある**
- 共通側はStripeカスタマーIDの紐付けのみ
- TDBS側はカード詳細（複数カード・デフォルト設定）を管理

**移行案:**
- 共通側に `UserPaymentCard` モデルを新設
- TDBS Cardの機能を共通化
- または `UserStripeCustomer` を拡張

---

## 4. TDBS固有モデル（重複なし）

以下のモデルは共通モデルに相当するものがなく、TDBS固有として維持される。

| モデル | 説明 | 移行時の対応 |
|--------|------|-------------|
| `Shop` | 店舗・施設情報 | そのまま共通化可能（`Facility`にリネーム検討） |
| `Reservation` | 予約本体 | 予約システムの中核として共通化 |
| `Coupon` | クーポン | EC/予約共通のクーポン機能として共通化可能 |
| `Option` | 追加オプション | 予約システム固有として維持 |
| `PaymentHistory` | 決済履歴 | トランザクションログとして共通化可能 |
| `BookingTmp` | 一時予約 | 予約システム固有として維持 |
| `Holiday` | 祝日マスター | 共通マスターとして共通化 |
| `Contact` | 問い合わせ | 汎用問い合わせとして共通化可能 |

---

## 5. 統合計画サマリー

### 5.1 統合カテゴリ分類

| カテゴリ | モデル | 対応方針 |
|---------|--------|---------|
| **✅ 完全統合** | Prefecture | 共通モデルに統合 |
| **✅ 条件付き統合** | SystemSetting, Holiday | 共通モデルを拡張して統合 |
| **⚠️ リネーム後統合** | News → TenantNews, Schedule → ReservationSlot, Plan → ServicePlan | 名称変更して共存 |
| **⚠️ 検討必要** | Account↔User, Card↔UserStripeCustomer, UserPlan↔UserPurchasedPlan | 認証統合・決済統合の設計が必要 |
| **❌ 別概念維持** | PaymentPlan（TDBS版は決済記録） | 役割が異なるため別名で維持 |
| **➡️ 共通化** | Shop, Reservation, Coupon, Option, PaymentHistory, BookingTmp, Contact | TDBS固有から共通モデルへ昇格 |

---

### 5.2 推奨リネーム一覧

| 現在のTDBSモデル名 | 推奨名称 | 理由 |
|-------------------|---------|------|
| `Tdbs\News` | `FacilityNews` または `TenantNews` | 共通Newsとの区別 |
| `Tdbs\Schedule` | `ReservationSlot` または `BookingSlot` | カレンダーScheduleとの区別 |
| `Tdbs\Plan` | `ServicePlan` または `FacilityPlan` | サブスクPlanとの区別 |
| `Tdbs\PaymentPlan` | `PlanPurchaseRecord` または `PlanPayment` | マスターPaymentPlanとの区別 |
| `Tdbs\UserPlan` | `FacilitySubscription` または `ServiceSubscription` | 共通UserPurchasedPlanとの区別 |
| `Tdbs\Account` | `FacilityMember` または `TenantUser` | 共通Userとの区別（または統合） |
| `Tdbs\Shop` | `Facility` | より汎用的な名称 |

---

### 5.3 統合優先度

| 優先度 | モデル | 理由 |
|-------|--------|------|
| **高** | Prefecture | 完全重複、即座に統合可能 |
| **高** | Holiday | マスターデータ、統合容易 |
| **中** | SystemSetting | 拡張設計が必要 |
| **中** | News, Schedule, Plan | リネームと参照修正が必要 |
| **低** | Account, Card, UserPlan | 認証・決済の設計検討が必要 |

---

## 6. 次のアクション

1. **Prefecture統合の実施**（最優先・リスク低）
   - 共通Prefectureを参照するよう外部キー変更
   - TDBS Prefectureモデルを廃止

2. **リネーム計画の策定**
   - 影響範囲の洗い出し（UseCase, Action, Request, Resource）
   - 段階的リネーム実施

3. **認証統合の設計**
   - Account↔User の関係性定義
   - LINE認証の共通化検討

4. **決済統合の設計**
   - Card/PaymentHistory の共通化
   - Stripe連携の統一
