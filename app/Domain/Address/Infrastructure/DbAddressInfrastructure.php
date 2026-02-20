<?php

namespace App\Domain\Address\Infrastructure;

use App\Domain\Address\Repository\AddressRepository;
use App\Models\UserAddress;
use App\Models\UserAddressJp;
use App\Models\UserAddressVn;
use App\Components\CommonComponent;
use Illuminate\Support\Facades\DB;

class DbAddressInfrastructure implements AddressRepository
{
    public function getAllByUserId(int $userId): array
    {
        $addresses = UserAddress::where('user_id', $userId)
            ->with(['jpDetail', 'vnDetail'])
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return $addresses->map(function (UserAddress $address) {
            $data = [
                'id' => $address->id,
                'label' => $address->label,
                'country_code' => $address->country_code,
                'input_mode' => $address->input_mode,
                'postal_code' => $address->postal_code,
                'phone' => $address->phone,
                'image_url' => $address->image_url ? CommonComponent::getFullUrl($address->image_url) : null,
                'is_default' => $address->is_default,
                'created_at' => $address->created_at?->format('Y-m-d H:i:s'),
            ];

            if ($address->country_code === 'JP' && $address->jpDetail) {
                $data['jp_detail'] = [
                    'prefecture' => $address->jpDetail->prefecture,
                    'city' => $address->jpDetail->city,
                    'ward_town' => $address->jpDetail->ward_town,
                    'banchi' => $address->jpDetail->banchi,
                    'building_name' => $address->jpDetail->building_name,
                    'room_no' => $address->jpDetail->room_no,
                ];
            }

            if ($address->country_code === 'VN' && $address->vnDetail) {
                $data['vn_detail'] = [
                    'province_city' => $address->vnDetail->province_city,
                    'district' => $address->vnDetail->district,
                    'ward_commune' => $address->vnDetail->ward_commune,
                    'detail_address' => $address->vnDetail->detail_address,
                    'building_name' => $address->vnDetail->building_name,
                    'room_no' => $address->vnDetail->room_no,
                ];
            }

            return $data;
        })->toArray();
    }

    public function getById(int $addressId, int $userId): ?array
    {
        $address = UserAddress::where('id', $addressId)
            ->where('user_id', $userId)
            ->with(['jpDetail', 'vnDetail'])
            ->first();

        if (!$address) {
            return null;
        }

        $data = [
            'id' => $address->id,
            'label' => $address->label,
            'country_code' => $address->country_code,
            'input_mode' => $address->input_mode,
            'postal_code' => $address->postal_code,
            'phone' => $address->phone,
            'image_url' => $address->image_url ? CommonComponent::getFullUrl($address->image_url) : null,
            'is_default' => $address->is_default,
            'created_at' => $address->created_at?->format('Y-m-d H:i:s'),
        ];

        if ($address->country_code === 'JP' && $address->jpDetail) {
            $data['jp_detail'] = [
                'prefecture' => $address->jpDetail->prefecture,
                'city' => $address->jpDetail->city,
                'ward_town' => $address->jpDetail->ward_town,
                'banchi' => $address->jpDetail->banchi,
                'building_name' => $address->jpDetail->building_name,
                'room_no' => $address->jpDetail->room_no,
            ];
        }

        if ($address->country_code === 'VN' && $address->vnDetail) {
            $data['vn_detail'] = [
                'province_city' => $address->vnDetail->province_city,
                'district' => $address->vnDetail->district,
                'ward_commune' => $address->vnDetail->ward_commune,
                'detail_address' => $address->vnDetail->detail_address,
                'building_name' => $address->vnDetail->building_name,
                'room_no' => $address->vnDetail->room_no,
            ];
        }

        return $data;
    }

    public function create(int $userId, array $masterData, array $detailData): ?int
    {
        try {
            DB::beginTransaction();

            $address = new UserAddress();
            $address->fill(array_merge($masterData, ['user_id' => $userId]));

            if ($masterData['is_default'] ?? false) {
                UserAddress::where('user_id', $userId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            if (!$address->save()) {
                DB::rollBack();
                return null;
            }

            $countryCode = $masterData['country_code'];

            if ($countryCode === 'JP' && !empty($detailData)) {
                $jpDetail = new UserAddressJp();
                $jpDetail->address_id = $address->id;
                $jpDetail->fill($detailData);
                if (!$jpDetail->save()) {
                    DB::rollBack();
                    return null;
                }
            }

            if ($countryCode === 'VN' && !empty($detailData)) {
                $vnDetail = new UserAddressVn();
                $vnDetail->address_id = $address->id;
                $vnDetail->fill($detailData);
                if (!$vnDetail->save()) {
                    DB::rollBack();
                    return null;
                }
            }

            DB::commit();
            return $address->id;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update(int $addressId, int $userId, array $masterData, array $detailData): bool
    {
        try {
            DB::beginTransaction();

            $address = UserAddress::where('id', $addressId)
                ->where('user_id', $userId)
                ->first();

            if (!$address) {
                DB::rollBack();
                return false;
            }

            // Reset is_default of other addresses if this one becomes default
            if ($masterData['is_default'] ?? false) {
                UserAddress::where('user_id', $userId)
                    ->where('id', '!=', $addressId)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $address->fill($masterData);
            if (!$address->save()) {
                DB::rollBack();
                return false;
            }

            $countryCode = $address->country_code;

            if ($countryCode === 'JP' && !empty($detailData)) {
                $jpDetail = UserAddressJp::find($addressId);
                if ($jpDetail) {
                    $jpDetail->fill($detailData);
                    if (!$jpDetail->save()) {
                        DB::rollBack();
                        return false;
                    }
                }
            }

            if ($countryCode === 'VN' && !empty($detailData)) {
                $vnDetail = UserAddressVn::find($addressId);
                if ($vnDetail) {
                    $vnDetail->fill($detailData);
                    if (!$vnDetail->save()) {
                        DB::rollBack();
                        return false;
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete(int $addressId, int $userId): bool
    {
        try {
            $address = UserAddress::where('id', $addressId)
                ->where('user_id', $userId)
                ->first();

            if (!$address) {
                return false;
            }

            return (bool) $address->delete();
        } catch (\Exception $e) {
            return false;
        }
    }
}
