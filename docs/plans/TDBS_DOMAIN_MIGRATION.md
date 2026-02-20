# Domain層 TDBS削除 移行計画

## 概要
`app/Domain/Tdbs/` 配下の全機能を、TDBSプレフィックスを使わない適切なドメインに移行する。

## 現状構造

```
app/Domain/Tdbs/
├── Auth/                    # LINE認証、ユーザー登録
├── Contact/                 # お問い合わせ送信
├── Member/                  # 管理者向けメンバー管理
├── Golph/
│   ├── Plan/               # ゴルフプラン管理（管理者向け）
│   └── Reservation/        # ゴルフ予約管理（管理者向け）
├── Sauna/
│   └── Reservation/        # サウナ予約管理（管理者向け）
├── StyleUser/
│   ├── Card/               # ユーザー自身のカード
│   ├── Profile/            # ユーザー自身のプロフィール
│   ├── Reservation/        # ユーザー自身の予約履歴
│   ├── News/               # ユーザー向けニュース
│   ├── Stripe/             # Stripeトークン取得
│   ├── Golph/
│   │   ├── Coupon/         # ユーザー向けクーポン
│   │   ├── Plan/           # ユーザーのプラン契約
│   │   ├── Reservation/    # ユーザーのゴルフ予約・決済
│   │   └── Schedule/       # ゴルフスケジュール閲覧
│   └── Sauna/
│       ├── Reservation/    # ユーザーのサウナ予約・決済
│       └── Schedule/       # サウナスケジュール閲覧
├── Components/              # 共通コンポーネント
└── Entity/                  # 共通Entity
```

---

## 移行計画

### Phase 1: 既存ドメインへの統合

#### Phase 1-1: 共通部品の移行 ✅ 完了 (2026-01-27)
- [x] `Tdbs/Components/TdbsCommonComponent.php` → `Shared/Components/CommonComponent.php`
- [x] `Tdbs/Entity/StatusEntity.php` → `Shared/Entity/StatusEntity.php`
- [x] 全参照箇所の名前空間更新

#### Phase 1-2: Tdbs/Auth → Line ✅ 完了 (2026-01-27)
- [x] `GetLineAuthUrlUseCase` → `Line/UseCase/GetLineAuthUrlUseCase`
- [x] `UserRegisterUseCase` → `Line/UseCase/UserRegisterUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行
- [x] Controller(Action)参照の更新
- [x] Route参照の更新
- [x] ServiceProvider更新

#### Phase 1-3: Tdbs/Contact → ContactMessage ✅ 完了 (2026-01-27)
- [x] `CreateContactUseCase` → `ContactMessage/UseCase/CreateMemberContactUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行
- [x] Controller(Action)参照の更新
- [x] Route参照の更新
- [x] ServiceProvider更新

#### Phase 1-4: Tdbs/StyleUser/News → News ✅ 完了 (2026-01-27)
- [x] `GetTopNewsUseCase` → `News/UseCase/GetTopMemberNewsUseCase`
- [x] `GetUserNewsUseCase` → `News/UseCase/GetMemberNewsUseCase`
- [x] `GetUserNewsDetailUseCase` → `News/UseCase/GetMemberNewsDetailUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行
- [x] Controller(Action)参照の更新
- [x] Route参照の更新
- [x] ServiceProvider更新

#### Phase 1-5: Tdbs/StyleUser/Stripe → Stripe ✅ 完了 (2026-01-27)
- [x] `GetStripeTokenUseCase` → `Stripe/UseCase/GetMemberStripeTokenUseCase`
- [x] 関連Entity, Factory移行
- [x] Controller(Action)参照の更新
- [x] Route参照の更新

---

### Phase 2: 新規ドメイン作成

#### Phase 2-1: Tdbs/Member → Member（新規作成）✅ 完了 (2026-01-27)
- [x] `GetAllMembersUseCase`
- [x] `GetMemberDetailUseCase`
- [x] `GetMemberPlanUseCase`
- [x] `GetMemberReservationsUseCase`
- [x] `GetMemberReservationsUsedUseCase`
- [x] `UpdateMemberUseCase`
- [x] `UpdateMemberPlanUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

#### Phase 2-2: Tdbs/Golph/Reservation + Tdbs/Sauna/Reservation → Reservation（新規作成）✅ 完了 (2026-01-27)
ゴルフ・サウナ共通の予約管理ドメイン（管理者向け）

**注意:** Golph/Plan は別ドメイン（TdbsPlan）として既存維持。

**Golph/Reservation:**
- [x] `CancelReservationInDbUseCase` → `Reservation/UseCase/CancelGolphReservationInDbUseCase`
- [x] `CancelReservationUseCase` → `Reservation/UseCase/CancelGolphReservationUseCase`
- [x] `GetReservationDetailUseCase` → `Reservation/UseCase/GetGolphReservationDetailUseCase`
- [x] `GetReservationsUseCase` → `Reservation/UseCase/GetGolphReservationsUseCase`

**Sauna/Reservation:**
- [x] `CancelReservationInDbUseCase` → `Reservation/UseCase/CancelSaunaReservationInDbUseCase`
- [x] `CancelReservationUseCase` → `Reservation/UseCase/CancelSaunaReservationUseCase`
- [x] `GetAllReservationsUseCase` → `Reservation/UseCase/GetSaunaReservationsUseCase`
- [x] `GetReservationDetailUseCase` → `Reservation/UseCase/GetSaunaReservationDetailUseCase`

#### Phase 2-3: Tdbs/StyleUser/Card → UserCard（新規作成）✅ 完了 (2026-01-27)
- [x] `GetUserCardUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

#### Phase 2-4: Tdbs/StyleUser/Profile → UserProfile（新規作成）✅ 完了 (2026-01-27)
- [x] `GetUserProfileUseCase`
- [x] `UpdateUserProfileUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

#### Phase 2-5: Tdbs/StyleUser/Reservation + StyleUser/Golph/Reservation + StyleUser/Sauna/Reservation → UserReservation（新規作成）✅ 完了 (2026-01-27)
ユーザー自身の予約操作

**StyleUser/Reservation:**
- [x] `CancelReservationUseCase`
- [x] `GetReservationHistoryUseCase`
- [x] `GetUserReservationConfirmUseCase`

**StyleUser/Golph/Reservation:**
- [x] `GetReservationListUseCase` → `GetGolphReservationListUseCase`
- [x] `PaymentGolphUseCase`

**StyleUser/Sauna/Reservation:**
- [x] `GetReservationListUseCase` → `GetSaunaReservationListUseCase`
- [x] `PaymentSaunaUseCase`

#### Phase 2-6: Tdbs/StyleUser/Golph/Plan → UserPlan（新規作成）✅ 完了 (2026-01-27)
- [x] `ChangeUserGolphPlanUseCase`
- [x] `CreateUserGolphPlanUseCase`
- [x] `GetUserGolphPlanUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

#### Phase 2-7: Tdbs/StyleUser/Golph/Coupon → UserCoupon（新規作成）✅ 完了 (2026-01-27)
- [x] `GetUserCouponsUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

#### Phase 2-8: Tdbs/StyleUser/Golph/Schedule + StyleUser/Sauna/Schedule → UserSchedule（新規作成）✅ 完了 (2026-01-27)
- [x] `GetScheduleDetailUseCase` (Golph)
- [x] `GetScheduleDetailUseCase` (Sauna)
- [x] 関連Entity, Repository, Factory, Infrastructureの移行

---

### Phase 3: クリーンアップ ✅ 完了 (2026-01-27)

- [x] `Tdbs/Components/`, `Tdbs/Entity/`, `Tdbs/StyleUser/`, `Tdbs/Sauna/` 削除
- [x] 移行対象の `Tdbs` 参照がないことを確認
- [ ] テスト実行・動作確認（手動で実施）

---

### Phase 4: Tdbs/Golph/Plan → GolphPlan ✅ 完了 (2026-01-27)

管理者向けプラン管理機能

- [x] `GetAllPlansUseCase` → `GolphPlan/UseCase/GetAllGolphPlansUseCase`
- [x] `GetPlanDetailUseCase` → `GolphPlan/UseCase/GetGolphPlanDetailUseCase`
- [x] `CreatePlanUseCase` → `GolphPlan/UseCase/CreateGolphPlanUseCase`
- [x] `UpdatePlanUseCase` → `GolphPlan/UseCase/UpdateGolphPlanUseCase`
- [x] `DeletePlanUseCase` → `GolphPlan/UseCase/DeleteGolphPlanUseCase`
- [x] 関連Entity, Repository, Factory, Infrastructureの移行
- [x] `app/Domain/Tdbs/` ディレクトリを完全削除
- [x] `TdbsDomainProvider`, `TdbsPlanDomainProvider`, `MemberGolphCouponDomainProvider` 削除

---

## 移行後のドメイン構造

```
app/Domain/
├── Line/                    ← Tdbs/Auth 統合
├── ContactMessage/          ← Tdbs/Contact 統合
├── News/                    ← Tdbs/StyleUser/News 統合
├── Stripe/                  ← Tdbs/StyleUser/Stripe 統合
├── Shared/                  ← Tdbs/Components, Tdbs/Entity 統合
│   ├── Components/
│   │   └── CommonComponent.php
│   └── Entity/
│       └── StatusEntity.php
│
├── Member/                  ← Tdbs/Member（新規）
├── Reservation/             ← Tdbs/Golph/* + Tdbs/Sauna/*（新規）
├── UserCard/                ← Tdbs/StyleUser/Card（新規）
├── UserProfile/             ← Tdbs/StyleUser/Profile（新規）
├── UserReservation/         ← Tdbs/StyleUser/Reservation + Golph/Reservation + Sauna/Reservation（新規）
├── UserPlan/                ← Tdbs/StyleUser/Golph/Plan（新規）
├── UserCoupon/              ← Tdbs/StyleUser/Golph/Coupon（新規）
├── UserSchedule/            ← Tdbs/StyleUser/Golph/Schedule + Sauna/Schedule（新規）
├── GolphPlan/               ← Tdbs/Golph/Plan（新規）
└── ... (既存維持)
```

---

## 各Phase作業手順

1. 移行先ディレクトリ作成（Entity, Factory, Repository, UseCase, Infrastructure）
2. ファイルコピー＆名前空間変更
3. ServiceProvider更新（Repository Binding）
4. Controller参照更新
5. Route確認
6. 旧ファイル削除
7. 動作確認

---

## 注意事項

- `TdbsCommonComponent` は多くのUseCaseから参照されているため、Phase 1-1で最初に移行する
- 移行時は1ドメインずつ完了させてから次に進む
- 各Phase完了時に本ファイルのチェックボックスを更新する

---

## 進捗状況

| Phase | ステータス | 完了日 |
|-------|----------|--------|
| Phase 1-1 | 完了 | 2026-01-27 |
| Phase 1-2 | 完了 | 2026-01-27 |
| Phase 1-3 | 完了 | 2026-01-27 |
| Phase 1-4 | 完了 | 2026-01-27 |
| Phase 1-5 | 完了 | 2026-01-27 |
| Phase 2-1 | 完了 | 2026-01-27 |
| Phase 2-2 | 完了 | 2026-01-27 |
| Phase 2-3 | 完了 | 2026-01-27 |
| Phase 2-4 | 完了 | 2026-01-27 |
| Phase 2-5 | 完了 | 2026-01-27 |
| Phase 2-6 | 完了 | 2026-01-27 |
| Phase 2-7 | 完了 | 2026-01-27 |
| Phase 2-8 | 完了 | 2026-01-27 |
| Phase 3   | 完了 | 2026-01-27 |
| Phase 4   | 完了 | 2026-01-27 |
