# TDBSモデル詳細説明

**作成日:** 2026-01-25
**対象:** `api/app/Models/Tdbs/` 配下の全17クラス

---

## 基底クラス: TdbsModel

**ファイル:** [TdbsModel.php](../../app/Models/TdbsModel.php)

全TDBSモデルの基底クラス。`tdbs_`テーブルプレフィックスを自動付与し、データベース分離を実現する。Auditable traitにより全モデルで変更履歴が追跡される。`getTable()`メソッドをオーバーライドし、子クラスが明示的にテーブル名を指定しなくても`tdbs_accounts`、`tdbs_shops`のように自動的にプレフィックスが付与される仕組みになっている。

---

## 1. Account（会員アカウント）

**ファイル:** [Account.php](../../app/Models/Tdbs/Account.php)
**テーブル:** `tdbs_accounts`

TDBSシステムの会員情報を管理する中核モデル。個人情報（氏名・住所・電話番号・生年月日）、認証情報（パスワード・LINE連携）、アカウント状態（管理者フラグ・認証済みフラグ・ブラックリストフラグ）を保持する。LINE認証によるソーシャルログインに対応し、`line_id`、`line_user_id`、`line_access_token`でLINE連携を管理。決済状態の追跡や請求先住所の管理も行い、`UserPlan`との1対1リレーションで会員の契約プラン情報にアクセスできる。SoftDeletesにより論理削除対応。

**主なリレーション:**
- `userPlan()` → UserPlan（現在のプラン）
- `lastUserPlan()` → UserPlan（最新のプラン履歴）

---

## 2. BookingTmp（一時予約）

**ファイル:** [BookingTmp.php](../../app/Models/Tdbs/BookingTmp.php)
**テーブル:** `tdbs_booking_tmps`

予約フロー中の一時的な予約状態を保持するモデル。決済処理中やセッション途中の予約情報を一時保存し、決済完了後に正式な`Reservation`レコードへ変換される設計。`shop_id`と`booking_datetime`のみを持つシンプルな構造で、カート機能のような役割を果たす。未完了の予約は定期的にクリーンアップされる想定。SoftDeletesなしで物理削除されるため、一時データとしての性質を持つ。

---

## 3. Card（決済カード）

**ファイル:** [Card.php](../../app/Models/Tdbs/Card.php)
**テーブル:** `tdbs_cards`

会員の決済カード情報を管理するモデル。Stripe連携のため`card_id`（Stripeトークン）は`$hidden`で非公開にし、`last4`（下4桁）、`brand`（VISA等）、`exp_month/exp_year`（有効期限）のみ表示可能。1アカウントに複数カードを紐付け可能で、`default_flag`でメイン決済カードを指定。`last_using_date`で最終使用日を追跡し、セキュリティ監査に対応。`status`でカードの有効/無効を管理し、SoftDeletesで削除済みカードの履歴も保持。

---

## 4. Contact（お問い合わせ）

**ファイル:** [Contact.php](../../app/Models/Tdbs/Contact.php)
**テーブル:** `tdbs_contacts`

顧客からの問い合わせ・サポートリクエストを保存するモデル。`email`、氏名（漢字/カナ）、`birthday`、`contact_type`（問い合わせ種別）、`content`（問い合わせ内容）を保持。他のモデルとの直接的なリレーションは持たず、スタンドアロンで動作。問い合わせ種別により分類・フィルタリングが可能。監査証跡としてAuditableトレイトを使用し、SoftDeletesで削除後も履歴参照が可能。

---

## 5. Coupon（クーポン）

**ファイル:** [Coupon.php](../../app/Models/Tdbs/Coupon.php)
**テーブル:** `tdbs_coupons`
**ドメイン:** `app/Domain/Tdbs/Golph/Coupon/`

プロモーション用割引クーポンを管理するモデル。`code`（UUIDクーポンコード）、`discount`（割引額/率）、`coupon_type`（定額/定率）、`status`（有効/無効/期限切れ）を持つ。有効期限は`expire_type`で制御し、固定日付（`expire_start_date`〜`expire_end_date`）またはローリング期間を設定可能。`limit`で総使用回数、`maximum_account`でアカウント毎の使用上限、`target_user`（JSON配列）で対象ユーザー種別を制限。Shop単位で管理され、ReservationやPaymentPlanで割引適用時に参照される。

---

## 6. Holiday（祝日・休日）

**ファイル:** [Holiday.php](../../app/Models/Tdbs/Holiday.php)
**テーブル:** `tdbs_holidays`

システム全体の祝日・特別日を定義するマスターデータモデル。`date`と`name`のみのシンプルな構造で、スケジュール料金計算時に参照される。平日料金（`normal_price`）と休日料金（`holiday_price`）の切り替え判定に使用。日本の祝日やゴールデンウィーク、年末年始等の特別料金適用日を管理。SoftDeletesなしで論理削除は行わず、過去の祝日データも維持される。

---

## 7. News（お知らせ）

**ファイル:** [News.php](../../app/Models/Tdbs/News.php)
**テーブル:** `tdbs_news`
**ドメイン:** `app/Domain/Tdbs/News/`

システムからのお知らせ・アナウンスを管理するモデル。`title`、`description`（本文）、`publish_date`（公開日）、`publish_flag`（公開/下書き）、`creator_id`（作成者）を保持。公開日による予約投稿と、公開フラグによる下書き機能を実装。管理画面からCRUD操作が可能で、GetAllNewsUseCase、CreateNewsUseCase等のUseCaseを通じてビジネスロジックが実行される。SoftDeletesにより削除後もアーカイブとして参照可能。

---

## 8. Option（追加オプション）

**ファイル:** [Option.php](../../app/Models/Tdbs/Option.php)
**テーブル:** `tdbs_options`

予約時の追加サービス・オプションを定義するモデル。`shop_id`と`schedule_id`で所属を指定し、Shop全体またはSchedule固有のオプションを設定可能。`name`、`price`、`unit`（単位）、`type`（1=人数ベース、2=時間ベース）、`user_type`（ユーザー種別）、`is_active`（有効フラグ）を持つ。Scheduleとの`hasMany`リレーションで、特定時間枠に紐づくオプション（例：駐車場、インストラクター）を管理。Reservationでは`option_id`、`option_type1_id`、`option_type2_id`として最大3つのオプションを適用可能。

---

## 9. PaymentHistory（決済履歴）

**ファイル:** [PaymentHistory.php](../../app/Models/Tdbs/PaymentHistory.php)
**テーブル:** `tdbs_payment_histories`

個別予約の決済履歴を記録するモデル。`shop_id`、`account_id`、`reservation_id`、`card_id`、`schedule_id`で関連エンティティを参照。`payment_intent_id`（Stripe決済ID）、`usage_type`（利用種別）、`total_amount`（合計金額）、`payment_status`（決済状態）を追跡。請求先住所（`billing_*`フィールド群）を決済時点でスナップショット保存し、後の住所変更に影響されない。Reservationとは別テーブルで決済情報を管理することで、予約と決済の関心を分離。

---

## 10. PaymentPlan（プラン決済）

**ファイル:** [PaymentPlan.php](../../app/Models/Tdbs/PaymentPlan.php)
**テーブル:** `tdbs_payment_plans`

サブスクリプションプランの購入・決済を記録するモデル。`account_id`、`plan_id`、`card_id`で会員・プラン・カードを紐付け。`payment_intent_id`（Stripe決済ID）、`total_amount`（支払額）、`payment_status`（決済状態）で決済状況を管理。`coupon_id`と`discount_amount`でクーポン適用による割引を記録。PaymentHistory（都度利用決済）とは異なり、プラン契約時の一括または定期決済を追跡する。UserPlanから参照され、契約・更新の決済証跡となる。

---

## 11. Plan（サービスプラン）

**ファイル:** [Plan.php](../../app/Models/Tdbs/Plan.php)
**テーブル:** `tdbs_plans`
**ドメイン:** `app/Domain/Tdbs/Golph/Plan/`

施設が提供するサービスプラン・コースを定義する中核モデル。`name`、`code`（一意識別子）、`shop_id`、`status`（有効/無効/アーカイブ）、`price`（基本料金）を持つ。`start_time`〜`end_time`で利用可能時間、`available_reservation`（JSON配列）で予約可能曜日、`available_from/to_*`フィールド群で予約受付期間を柔軟に設定。`accompanying_slots`（同伴者枠）、`limit`/`no_limit`で定員管理。`charge_people_price`（追加人数料金）、`charge_time_price_1/2/3`（延長料金3段階）で複雑な料金体系に対応。`stripe_payment_link`でStripe決済リンクを保持。

---

## 12. Prefecture（都道府県）

**ファイル:** [Prefecture.php](../../app/Models/Tdbs/Prefecture.php)
**テーブル:** `tdbs_prefectures`

日本の都道府県マスターデータモデル。`name`（都道府県名）と`order_num`（表示順）のみを持つシンプルな構造。AccountやPaymentHistoryの住所情報で`prefecture_id`として参照される。UIでのプルダウン表示順を`order_num`で制御し、北海道から沖縄までの標準的な並び順を実現。SoftDeletesを使用するが、マスターデータのため基本的に削除されない想定。

---

## 13. Reservation（予約）

**ファイル:** [Reservation.php](../../app/Models/Tdbs/Reservation.php)
**テーブル:** `tdbs_reservations`

会員の施設予約を管理する重要モデル。`shop_id`、`account_id`、`schedule_id`、`user_plan_id`、`coupon_id`で関連エンティティを参照。`reservation_number`（予約番号）、`usage_date`（利用日）、`usage_time_start/end`（利用時間）、`usage_type`（日帰り/宿泊）、`status`（予約状態）、`total_amount`（合計金額）を持つ。料金は予約時点でスナップショット保存（`day_trip_normal_price`等）し、後の料金改定に影響されない。`option_id/price`、`parking_*`、`instructor_*`、`lesson_*`で追加サービスを記録。`charge_id`、`payment_intent_id`でStripe決済連携。`registered_at`→`approved_at`→`canceled_at`でステータス遷移を追跡。

---

## 14. Schedule（スケジュール枠）

**ファイル:** [Schedule.php](../../app/Models/Tdbs/Schedule.php)
**テーブル:** `tdbs_schedules`
**ドメイン:** `app/Domain/Tdbs/Golph/Schedule/`

予約可能な時間枠を定義するモデル。`shop_id`、`date`（日付）、`start_time`〜`end_time`（時間帯）、`schedule_type`（日帰り/宿泊）、`status`（予約可/満席/休止）を持つ。料金設定として`day_trip_normal_price`、`day_trip_holiday_price`、`stay_normal_price`、`stay_holiday_price`を保持し、Shopのデフォルト料金を上書き可能。`parking_flag/price`、`instructor_flag/price`、`lesson_flag/price`でオプションサービスの可否と料金を枠ごとに設定。`hasMany(Option)`で枠固有の追加オプションを管理。CSVインポート機能（ImportScheduleUseCase）で一括登録に対応。

---

## 15. Shop（店舗・施設）

**ファイル:** [Shop.php](../../app/Models/Tdbs/Shop.php)
**テーブル:** `tdbs_shops`
**ドメイン:** `app/Domain/Tdbs/Shop/`

サービス提供施設を管理する中核モデル。`name`、`description`、`shop_type`（ゴルフ/サウナ）、`line_id`（LINE公式アカウント）を持つ。`images`（JSON配列）、`image_top`、`image_nav`で画像管理し、`getImagesAttribute`アクセサでフルURLを生成。`address`、`map`（Google Maps埋め込み）、`map_link`で所在地情報を提供。デフォルト料金（`day_trip_*_price`、`stay_*_price`）と追加サービス（`parking_*`、`instructor_*_1/2/3`、`lesson_*_1/2/3`）を3段階で設定可能。`access_token`、`refresh_token`で外部API連携。`hasMany(Option)`で店舗共通オプションを管理。

---

## 16. SystemSetting（システム設定）

**ファイル:** [SystemSetting.php](../../app/Models/Tdbs/SystemSetting.php)
**テーブル:** `tdbs_system_settings`
**ドメイン:** `app/Domain/Tdbs/SystemSetting/`

システム全体または店舗固有の設定を保存するKey-Valueストアモデル。`shop_id`（nullでシステム全体）、`type`（設定種別）、`content`（設定値、JSONまたは文字列）を持つ。利用規約、プライバシーポリシー、メール文面等の可変テキストや、機能フラグ、制限値等の設定を管理。DetailSystemSettingUseCase、UpdateSystemSettingUseCaseを通じて取得・更新され、`firstOrNew`パターンでUpsert操作を実現。SoftDeletesにより設定変更履歴も追跡可能。

---

## 17. UserPlan（会員プラン契約）

**ファイル:** [UserPlan.php](../../app/Models/Tdbs/UserPlan.php)
**テーブル:** `tdbs_user_plans`

会員のプラン契約・サブスクリプション状態を管理するモデル。`shop_id`、`account_id`、`plan_id`で基本的な紐付けを行い、`subscription_id`（Stripeサブスク）、`payment_plan_id`（決済記録）を参照。`next_month_plan_id`で翌月のプラン変更予約、`next_payment_card_id`で決済カード変更を事前設定可能。`payment_at`（決済日）、`expire_end`（有効期限）、`count_remaining`（残り利用回数）でサブスク状態を追跡。`charge_id`、`client_secret`でStripe決済フローを管理。Accountから`hasOne`で参照され、会員の現在の契約状態を表す。

---

## モデル間リレーション図

```
Account (会員)
├── Card (決済カード) [1:N]
├── UserPlan (契約プラン) [1:1]
│   ├── Plan (プラン定義)
│   └── PaymentPlan (プラン決済)
├── Reservation (予約) [1:N]
│   ├── Schedule (時間枠)
│   │   └── Option (オプション) [1:N]
│   ├── Coupon (クーポン)
│   └── PaymentHistory (決済履歴)
└── Contact (問い合わせ)

Shop (店舗)
├── Plan (プラン) [1:N]
├── Schedule (スケジュール) [1:N]
├── Coupon (クーポン) [1:N]
├── Option (オプション) [1:N]
├── News (お知らせ) [1:N]
└── SystemSetting (設定) [1:N]

Prefecture (都道府県) ← Account, PaymentHistory
Holiday (祝日) ← Schedule料金計算で参照
BookingTmp (一時予約) → Reservation
```

---

## ビジネスフロー

### 予約フロー
1. 会員（Account）がScheduleを選択
2. BookingTmpに一時保存
3. オプション（Option）を選択
4. クーポン（Coupon）を適用
5. カード（Card）で決済
6. Reservation作成、PaymentHistory記録
7. BookingTmp削除

### サブスクリプションフロー
1. 会員（Account）がPlanを選択
2. カード（Card）で決済
3. PaymentPlan作成
4. UserPlan作成/更新
5. 月次更新時にnext_month_plan_id適用
