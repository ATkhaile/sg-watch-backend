<?php

namespace App\Docs\Actions\Api\Address;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="住所",
 *     description="Address"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/addresses",
 *     summary="Get user addresses",
 *     description="Get all addresses for the authenticated user. Returns addresses sorted by is_default desc, created_at desc.",
 *     operationId="getAddresses",
 *     tags={"住所"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Addresses retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Addresses retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="label", type="string", example="自宅"),
 *                     @OA\Property(property="country_code", type="string", enum={"JP", "VN"}, example="JP"),
 *                     @OA\Property(property="input_mode", type="string", enum={"manual", "image_only"}, example="manual"),
 *                     @OA\Property(property="postal_code", type="string", nullable=true, example="150-0001"),
 *                     @OA\Property(property="phone", type="string", nullable=true, example="090-1234-5678"),
 *                     @OA\Property(property="image_url", type="string", nullable=true, example="https://example.com/storage/addresses/img001.jpg"),
 *                     @OA\Property(property="is_default", type="boolean", example=true),
 *                     @OA\Property(property="created_at", type="string", format="datetime", example="2026-01-15T10:00:00Z"),
 *                     @OA\Property(
 *                         property="jp_detail",
 *                         type="object",
 *                         nullable=true,
 *                         @OA\Property(property="prefecture", type="string", example="東京都"),
 *                         @OA\Property(property="city", type="string", example="渋谷区"),
 *                         @OA\Property(property="ward_town", type="string", example="神宮前"),
 *                         @OA\Property(property="banchi", type="string", example="1-2-3"),
 *                         @OA\Property(property="building_name", type="string", nullable=true, example="SGタワー"),
 *                         @OA\Property(property="room_no", type="string", nullable=true, example="101")
 *                     ),
 *                     @OA\Property(
 *                         property="vn_detail",
 *                         type="object",
 *                         nullable=true,
 *                         @OA\Property(property="province_city", type="string", example="Hồ Chí Minh"),
 *                         @OA\Property(property="district", type="string", example="Quận 1"),
 *                         @OA\Property(property="ward_commune", type="string", example="Phường Bến Nghé"),
 *                         @OA\Property(property="detail_address", type="string", example="123 Lê Lợi"),
 *                         @OA\Property(property="building_name", type="string", nullable=true, example="Vincom Center"),
 *                         @OA\Property(property="room_no", type="string", nullable=true, example="A-501")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Invalid or missing token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */
class GetAddressesAction {}
