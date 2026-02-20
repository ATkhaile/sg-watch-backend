<?php

namespace App\Http\Requests\Api\Address;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateAddressRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countryCode = $this->input('country_code');
        $inputMode = $this->input('input_mode', 'manual');

        $rules = [
            'label' => ['required', 'string', 'max:100'],
            'country_code' => ['required', Rule::in(['JP', 'VN'])],
            'input_mode' => ['required', Rule::in(['manual', 'image_only'])],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'is_default' => ['sometimes', 'boolean'],
        ];

        // JP + image_only: image required
        if ($countryCode === 'JP' && $inputMode === 'image_only') {
            $rules['image_url'] = ['required', 'string', 'max:500'];
        }

        // JP + manual: address fields + image required
        if ($countryCode === 'JP' && $inputMode === 'manual') {
            $rules['postal_code'] = ['required', 'string', 'max:20'];
            $rules['phone'] = ['required', 'string', 'max:30'];
            $rules['image_url'] = ['required', 'string', 'max:500'];
            $rules['jp_detail'] = ['required', 'array'];
            $rules['jp_detail.prefecture'] = ['required', 'string', 'max:50'];
            $rules['jp_detail.city'] = ['required', 'string', 'max:100'];
            $rules['jp_detail.ward_town'] = ['required', 'string', 'max:100'];
            $rules['jp_detail.banchi'] = ['required', 'string', 'max:100'];
            $rules['jp_detail.building_name'] = ['nullable', 'string', 'max:150'];
            $rules['jp_detail.room_no'] = ['nullable', 'string', 'max:50'];
        }

        // VN: always manual mode, all fields required
        if ($countryCode === 'VN') {
            $rules['input_mode'] = ['required', Rule::in(['manual'])];
            $rules['postal_code'] = ['required', 'string', 'max:20'];
            $rules['phone'] = ['required', 'string', 'max:30'];
            $rules['vn_detail'] = ['required', 'array'];
            $rules['vn_detail.province_city'] = ['required', 'string', 'max:100'];
            $rules['vn_detail.district'] = ['required', 'string', 'max:100'];
            $rules['vn_detail.ward_commune'] = ['required', 'string', 'max:100'];
            $rules['vn_detail.detail_address'] = ['required', 'string', 'max:255'];
            $rules['vn_detail.building_name'] = ['nullable', 'string', 'max:150'];
            $rules['vn_detail.room_no'] = ['nullable', 'string', 'max:50'];
        }

        return $rules;
    }
}
