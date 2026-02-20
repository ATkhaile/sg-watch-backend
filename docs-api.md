# SG Watch Backend - API Reference

> Base URL: `/api/v1`
> Auth levels: **Guest** (no auth) | **Member** (JWT + Session) | **Mobile** (JWT only)

---

## 1. Guest (No Auth)

### Auth

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `POST` | `/login` | Login |
| 2 | `POST` | `/login/google` | Google OAuth (Web) |
| 3 | `POST` | `/login/google-app` | Google OAuth (Mobile) |
| 4 | `POST` | `/verify-login` | Verify login |
| 5 | `POST` | `/register` | Register |
| 6 | `GET` | `/verify-registration/{token}` | Verify registration email |
| 7 | `POST` | `/web-session-auth` | Session auth (Web) |

### Password Reset

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 8 | `POST` | `/forgot-password` | Forgot password (send link) |
| 9 | `POST` | `/reset-password/{token}` | Reset password by link |
| 10 | `GET` | `/check-reset-token/{token}` | Check reset token |
| 11 | `POST` | `/password/otp/send` | Send OTP |
| 12 | `POST` | `/password/otp/verify` | Verify OTP |
| 13 | `POST` | `/password/reset` | Reset password by token |

### Webhooks

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 14 | `POST` | `/stripe/webhook` | Stripe webhook |
| 15 | `POST` | `/stripe/create-customer` | Create Stripe customer |
| 16 | `POST` | `/stripe/submit-request` | Submit cancel request |
| 17 | `POST` | `/stripe/check-code-request` | Check cancel code |

---

## 2. Member (auth.session - JWT + Session)

### 2.1 Auth / Profile

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/user-info` | Get my profile |
| 2 | `PUT` | `/update-profile` | Update my profile (first_name, last_name, gender, birthday, avatar_url) |
| 3 | `POST` | `/update-avatar` | Upload avatar |
| 4 | `DELETE` | `/delete-avatar` | Delete avatar |
| 5 | `GET` | `/logout` | Logout |
| 6 | `POST` | `/change-password` | Change password |
| 7 | `GET` | `/pending-email-change` | Get pending email change |
| 8 | `POST` | `/request-email-change` | Request email change |
| 9 | `POST` | `/confirm-email-change` | Confirm email change |
| 10 | `DELETE` | `/withdraw` | Delete account |
| 11 | `POST` | `/fcm_token` | Register FCM token |
| 12 | `DELETE` | `/fcm_token` | Delete FCM token |

### 2.2 Users (Admin)

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/users/list` | List users |
| 2 | `POST` | `/users/create` | Create user |
| 3 | `GET` | `/users/{id}` | Get user detail |
| 4 | `PUT` | `/users/{id}` | Update user |
| 5 | `DELETE` | `/users/{id}` | Delete user |
| 6 | `POST` | `/users/{id}/avatar` | Upload user avatar |
| 7 | `DELETE` | `/users/{id}/avatar` | Delete user avatar |
| 8 | `POST` | `/users/{id}/cover-image` | Upload cover image |
| 9 | `DELETE` | `/users/{id}/cover-image` | Delete cover image |
| 10 | `PUT` | `/users/{id}/admin` | Toggle admin |
| 11 | `POST` | `/users/{id}/permissions/reset` | Reset permissions |
| 12 | `PUT` | `/users/{id}/suspension` | Toggle suspension |
| 13 | `GET` | `/users/{id}/suspension-logs` | Get suspension logs |
| 14 | `GET` | `/users/{id}/stripe-customer-links` | List Stripe links |
| 15 | `POST` | `/users/{id}/stripe-customer-links` | Add Stripe link |
| 16 | `PUT` | `/users/{id}/stripe-customer-links/{linkId}` | Update Stripe link |
| 17 | `DELETE` | `/users/{id}/stripe-customer-links/{linkId}` | Delete Stripe link |
| 18 | `GET` | `/users/{id}/stripe-customer-links/{linkId}/portal` | Get portal link |
| 19 | `GET` | `/users/{id}/session-device` | List session devices |
| 20 | `DELETE` | `/users/session-device/{id}` | Delete session device |

### 2.3 Authorization (RBAC)

**Roles**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/authorization/roles/list` | List roles |
| 2 | `POST` | `/authorization/roles/create` | Create role |
| 3 | `GET` | `/authorization/roles/{id}` | Get role detail |
| 4 | `PUT` | `/authorization/roles/{id}` | Update role |
| 5 | `DELETE` | `/authorization/roles/{id}` | Delete role |

**Permissions**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 6 | `GET` | `/authorization/permissions/list` | List permissions |
| 7 | `POST` | `/authorization/permissions/create` | Create permission |
| 8 | `GET` | `/authorization/permissions/usecase-groups` | Get usecase groups |
| 9 | `POST` | `/authorization/permissions/{id}/toggle-active` | Toggle active |
| 10 | `GET` | `/authorization/permissions/{id}` | Get permission detail |
| 11 | `PUT` | `/authorization/permissions/{id}` | Update permission |
| 12 | `DELETE` | `/authorization/permissions/{id}` | Delete permission |

**User-Role-Permission Assignment**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 13 | `POST` | `/authorization/users/{id}/permissions/attach` | Attach permission to user |
| 14 | `DELETE` | `/authorization/users/{id}/permissions/revoke` | Revoke permission from user |
| 15 | `POST` | `/authorization/users/{id}/roles/attach` | Attach role to user |
| 16 | `DELETE` | `/authorization/users/{id}/roles/revoke` | Revoke role from user |
| 17 | `POST` | `/authorization/roles/{id}/permissions/attach` | Attach permission to role |
| 18 | `DELETE` | `/authorization/roles/{id}/permissions/revoke` | Revoke permission from role |
| 19 | `GET` | `/authorization/users/{id}/roles` | List user's roles |
| 20 | `GET` | `/authorization/users/{id}/permissions` | List user's permissions |
| 21 | `GET` | `/authorization/roles/{id}/permissions` | List role's permissions |

### 2.4 Chat

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/chat/users-for-chat` | Get users for chat |
| 2 | `POST` | `/chat/message` | Send message |
| 3 | `POST` | `/chat/messages/mark-as-read` | Mark as read |
| 4 | `GET` | `/chat/history/list` | Chat history |
| 5 | `GET` | `/chat/users` | Available users |
| 6 | `GET` | `/chat/partner/{partnerId}` | Get chat partner |
| 7 | `GET` | `/chat/conversations` | List conversations |
| 8 | `POST` | `/chat/room/join` | Join room |
| 9 | `POST` | `/chat/room/leave` | Leave room |
| 10 | `POST` | `/chat/typing/start` | Start typing |
| 11 | `POST` | `/chat/typing/stop` | Stop typing |
| 12 | `POST` | `/chat/link-preview` | Fetch link preview |

### 2.5 Notifications

**Notification Templates**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/notifications` | List notifications |
| 2 | `POST` | `/notifications` | Create notification |
| 3 | `GET` | `/notifications/{id}` | Get detail |
| 4 | `PUT` | `/notifications/{id}` | Update |
| 5 | `DELETE` | `/notifications/{id}` | Delete |

**Push Notifications**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 6 | `GET` | `/notification_pushs` | List push notifications |
| 7 | `POST` | `/notification_pushs` | Create push |
| 8 | `GET` | `/notification_pushs/{id}` | Get detail |
| 9 | `POST` | `/notification_pushs/{id}` | Update push |
| 10 | `DELETE` | `/notification_pushs/{id}` | Delete push |
| 11 | `GET` | `/notification_pushs/{id}/history` | Push history |

**Pusher Notifications**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 12 | `GET` | `/pusher/notifications` | List notifications |
| 13 | `GET` | `/pusher/notifications/unread` | Unread count |
| 14 | `PUT` | `/pusher/notifications/{id}/readed` | Mark as read |

**Firebase Notifications**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 15 | `GET` | `/firebase/notifications` | List notifications |
| 16 | `GET` | `/firebase/notifications/unread` | Unread count |
| 17 | `PUT` | `/firebase/notifications/{id}/readed` | Mark as read |

### 2.6 Stripe (Admin)

**Account Management**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/stripe/accounts/list` | List accounts |
| 2 | `POST` | `/stripe/accounts/create` | Create account |
| 3 | `POST` | `/stripe/accounts/test-connection` | Test connection |
| 4 | `GET` | `/stripe/accounts/{id}` | Get account detail |
| 5 | `PUT` | `/stripe/accounts/{id}` | Update account |
| 6 | `DELETE` | `/stripe/accounts/{id}` | Delete account |

**Account Data**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 7 | `GET` | `/stripe/accounts/customers/subscriptions` | Customer subscriptions |
| 8 | `GET` | `/stripe/accounts/customers/search` | Search customers |
| 9 | `GET` | `/stripe/accounts/{id}/products` | List products |
| 10 | `GET` | `/stripe/accounts/{id}/prices` | List prices |
| 11 | `GET` | `/stripe/accounts/{id}/payment-links` | List payment links |
| 12 | `GET` | `/stripe/accounts/{id}/transactions` | List transactions |
| 13 | `GET` | `/stripe/accounts/{id}/transactions/export` | Export transactions |

**Dashboard Stats**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 14 | `GET` | `/stripe/accounts/dashboard-stats/all` | All accounts stats |
| 15 | `POST` | `/stripe/accounts/dashboard-stats/refresh-all` | Refresh all stats |
| 16 | `GET` | `/stripe/accounts/{id}/dashboard-stats` | Account stats |
| 17 | `POST` | `/stripe/accounts/{id}/dashboard-stats/refresh` | Refresh account stats |

**Sync & Backfill**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 18 | `POST` | `/stripe/accounts/backfill-all` | Backfill all accounts |
| 19 | `POST` | `/stripe/accounts/{id}/backfill` | Backfill account |
| 20 | `POST` | `/stripe/accounts/{id}/sync` | Incremental sync |
| 21 | `GET` | `/stripe/accounts/{id}/sync-status` | Sync status |
| 22 | `GET` | `/stripe/accounts/{id}/sync-errors` | Sync errors |
| 23 | `POST` | `/stripe/accounts/{id}/sync-errors/resolve` | Resolve errors |
| 24 | `GET` | `/stripe/accounts/{id}/sync-progress` | Sync progress |
| 25 | `POST` | `/stripe/accounts/{id}/sync-payment-methods` | Sync payment methods |
| 26 | `POST` | `/stripe/accounts/{id}/sync-subscription-items` | Sync subscription items |

**Sync Jobs & Queue**

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 27 | `GET` | `/stripe/accounts/sync-settings` | Get sync settings |
| 28 | `PUT` | `/stripe/accounts/sync-settings` | Update sync settings |
| 29 | `GET` | `/stripe/accounts/sync-stats` | Sync stats |
| 30 | `GET` | `/stripe/accounts/sync-queue` | List sync queue |
| 31 | `DELETE` | `/stripe/accounts/sync-queue/{id}` | Delete queue job |
| 32 | `POST` | `/stripe/accounts/sync-queue/{id}/execute` | Execute queue job |
| 33 | `PUT` | `/stripe/accounts/sync-queue/{id}/reschedule` | Reschedule queue job |
| 34 | `POST` | `/stripe/accounts/sync-queue/{id}/cancel` | Cancel queue job |
| 35 | `POST` | `/stripe/accounts/sync-queue/enqueue-all` | Enqueue all accounts |
| 36 | `POST` | `/stripe/accounts/sync-queue/process` | Process sync queue |
| 37 | `GET` | `/stripe/accounts/sync-history` | Sync history |
| 38 | `GET` | `/stripe/accounts/db-stats` | DB stats |
| 39 | `GET` | `/stripe/accounts/{id}/sync-jobs` | List sync jobs |
| 40 | `GET` | `/stripe/accounts/{id}/sync-jobs/running` | Running jobs |
| 41 | `POST` | `/stripe/accounts/{id}/sync-jobs/{jobId}/cancel` | Cancel sync job |

### 2.7 Content

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/comments/list` | List comments |
| 2 | `DELETE` | `/comments/{id}` | Delete comment |

### 2.8 User Data

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `GET` | `/user/{user_id}/fcm_token` | Get user's FCM tokens |
| 2 | `POST` | `/user/receive_notification` | Update notification setting |
| 3 | `POST` | `/fcm_token/{id}/status` | Update FCM token status |

---

## 3. Mobile (auth.basic - JWT only)

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | `POST` | `/comments/create` | Create comment |
| 2 | `GET` | `/comments/{model}/{modelId}` | Get comments by model |
| 3 | `GET` | `/stripe/portal-link` | Get Stripe portal link |
| 4 | `POST` | `/stripe/cancel-request` | Request cancellation |
| 5 | `POST` | `/stripe/subscribe-plan` | Subscribe to plan |
| 6 | `POST` | `/stripe/create-payment-link` | Create payment link |
| 7 | `POST` | `/stripe/activate-membership` | Activate membership |

---

## Summary

| Group | Count |
|-------|-------|
| Guest | 17 |
| Member - Auth/Profile | 12 |
| Member - Users (Admin) | 20 |
| Member - Authorization | 21 |
| Member - Chat | 12 |
| Member - Notifications | 17 |
| Member - Stripe | 41 |
| Member - Content | 2 |
| Member - User Data | 3 |
| Mobile | 7 |
| **Total** | **152** |
