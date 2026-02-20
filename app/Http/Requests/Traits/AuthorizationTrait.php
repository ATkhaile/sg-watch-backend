<?php

namespace App\Http\Requests\Traits;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @deprecated 権限チェックはUseCaseのRequiresPermission Traitで行うように移行済み
 *             このTraitは後方互換性のために残しているが、authorize()は常にtrueを返す
 */
trait AuthorizationTrait
{
    /**
     * 認証チェックのみ行う（権限チェックはUseCaseで実施）
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function handleFailedAuthorization()
    {
        $response = Response::json([
            'message' => __('auth.unauthorized'),
            'status_code' => 403
        ], 403);

        throw new HttpResponseException($response);
    }

    protected function failedAuthorization()
    {
        $this->handleFailedAuthorization();
    }
}
