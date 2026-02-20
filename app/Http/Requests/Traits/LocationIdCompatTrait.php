<?php

namespace App\Http\Requests\Traits;

/**
 * shop_id と location_id の両方に対応するための互換性Trait
 *
 * ルートパラメータが {location_id} でも {shop_id} でも動作するようにする
 */
trait LocationIdCompatTrait
{
    /**
     * shop_id を location_id からも取得できるようにマージする
     */
    protected function mergeShopId(): void
    {
        $shopId = $this->route('shop_id') ?? $this->route('location_id');
        $this->merge(['shop_id' => $shopId]);
    }

    /**
     * location_id を shop_id からも取得できるようにマージする
     */
    protected function mergeLocationId(): void
    {
        $locationId = $this->route('location_id') ?? $this->route('shop_id');
        $this->merge(['location_id' => $locationId]);
    }
}
