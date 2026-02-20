<?php

namespace App\Domain\StripeAccount\UseCase;

use App\Domain\StripeAccount\Entity\TestStripeConnectionRequestEntity;
use App\Domain\StripeAccount\Entity\TestStripeConnectionResultEntity;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\SignatureVerificationException;
use App\Domain\Shared\Concerns\RequiresPermission;

final class TestStripeConnectionUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'test-stripe-connection';
    public function __invoke(TestStripeConnectionRequestEntity $requestEntity): TestStripeConnectionResultEntity
    {
        $this->authorize();

        try {
            // Stripe クライアントを秘密キーで初期化
            $stripe = new StripeClient($requestEntity->secretKey);

            // アカウント情報を取得して接続テスト
            $account = $stripe->accounts->retrieve('self');

            // テストモードかどうかを判定
            $isTestMode = str_starts_with($requestEntity->publicKey, 'pk_test_');

            // Webhook署名シークレットの検証
            $webhookSecretValid = null;
            $webhookSecretError = null;

            if ($requestEntity->webhookSecret) {
                $webhookValidation = $this->validateWebhookSecret($requestEntity->webhookSecret);
                $webhookSecretValid = $webhookValidation['valid'];
                $webhookSecretError = $webhookValidation['error'];
            }

            return new TestStripeConnectionResultEntity(
                success: true,
                accountId: $account->id,
                country: $account->country,
                defaultCurrency: $account->default_currency,
                chargesEnabled: $account->charges_enabled,
                payoutsEnabled: $account->payouts_enabled,
                businessType: $account->business_type,
                displayName: $account->settings?->dashboard?->display_name ?? $account->business_profile?->name ?? null,
                webhookSecretValid: $webhookSecretValid,
                webhookSecretError: $webhookSecretError,
            );
        } catch (AuthenticationException $e) {
            return new TestStripeConnectionResultEntity(
                success: false,
                error: '認証エラー: APIキーが無効です',
                errorCode: 'authentication_error',
            );
        } catch (ApiErrorException $e) {
            return new TestStripeConnectionResultEntity(
                success: false,
                error: 'Stripe APIエラー: ' . $e->getMessage(),
                errorCode: $e->getStripeCode() ?? 'api_error',
            );
        } catch (\Exception $e) {
            return new TestStripeConnectionResultEntity(
                success: false,
                error: '接続エラー: ' . $e->getMessage(),
                errorCode: 'connection_error',
            );
        }
    }

    /**
     * Webhook署名シークレットの形式を検証
     */
    private function validateWebhookSecret(string $webhookSecret): array
    {
        // 形式チェック: whsec_ で始まるかどうか
        if (!str_starts_with($webhookSecret, 'whsec_')) {
            return [
                'valid' => false,
                'error' => 'Webhook署名シークレットは whsec_ で始まる必要があります',
            ];
        }

        // 長さチェック: whsec_ (6文字) + シークレット部分 (通常64文字)
        $secretPart = substr($webhookSecret, 6);
        if (strlen($secretPart) < 32) {
            return [
                'valid' => false,
                'error' => 'Webhook署名シークレットの形式が不正です（シークレット部分が短すぎます）',
            ];
        }

        // 文字種チェック: 英数字のみ
        if (!preg_match('/^[a-zA-Z0-9]+$/', $secretPart)) {
            return [
                'valid' => false,
                'error' => 'Webhook署名シークレットの形式が不正です（英数字以外が含まれています）',
            ];
        }

        // テスト署名検証を試みる（ダミーペイロードで形式確認）
        try {
            $testPayload = json_encode(['test' => true]);
            $testTimestamp = time();
            $testSignature = 't=' . $testTimestamp . ',v1=' . hash_hmac('sha256', $testTimestamp . '.' . $testPayload, $webhookSecret);

            // 署名検証を実行（成功すれば形式は正しい）
            Webhook::constructEvent($testPayload, $testSignature, $webhookSecret);

            return [
                'valid' => true,
                'error' => null,
            ];
        } catch (SignatureVerificationException $e) {
            // 署名形式自体は正しいが、シークレットが一致しない場合もここに来る可能性
            // ただし、自分で生成した署名なので成功するはず
            return [
                'valid' => false,
                'error' => 'Webhook署名シークレットの検証に失敗しました: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'error' => 'Webhook署名シークレットの検証中にエラーが発生しました: ' . $e->getMessage(),
            ];
        }
    }
}
